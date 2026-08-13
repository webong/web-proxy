<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\WebhookClient\WebhookClientServiceProvider;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;
use Spatie\WebhookServer\WebhookServerServiceProvider;
use Zorvia\WebProxy\Headers;
use Zorvia\WebProxy\Jobs\ProcessWebhookJob;
use Zorvia\WebProxy\Models\WebProxyCall;
use Zorvia\WebProxy\Tests\Support\TestRouter;
use Zorvia\WebProxy\Tests\Support\TestSignatureValidator;
use Zorvia\WebProxy\Tests\Support\TestWebhookJob;
use Zorvia\WebProxy\Tests\Support\TestWebhookResponse;
use Zorvia\WebProxy\WebProxy;
use Zorvia\WebProxy\WebhookProxyRouteDefinition;
use Zorvia\WebProxy\WebProxyServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        app(WebProxy::class)->register('test-router', TestRouter::class);
    }

    /** @return list<class-string> */
    protected function getPackageProviders($app): array
    {
        return [
            WebhookClientServiceProvider::class,
            WebhookServerServiceProvider::class,
            WebProxyServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set([
            'app.key' => 'base64:'.base64_encode(str_repeat('a', 32)),
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'web-proxy.base_url' => 'https://proxy.example.test',
            'web-proxy.secret' => 'internal-forwarding-secret',
            'webhook-client.configs' => [[
                'name' => 'test-router',
                'signing_secret' => '',
                'signature_header_name' => '',
                'signature_validator' => TestSignatureValidator::class,
                'webhook_profile' => ProcessEverythingWebhookProfile::class,
                'webhook_response' => TestWebhookResponse::class,
                'webhook_model' => WebProxyCall::class,
                'store_headers' => ['X-Test-Header', Headers::ENDPOINT],
                'store_attachments' => false,
                'process_webhook_job' => ProcessWebhookJob::class,
            ]],
            'web-proxy.targets' => [
                'event' => [],
                'job' => [
                    'test-webhook-job' => TestWebhookJob::class,
                ],
            ],
        ]);

        $app->bind(
            WebhookProxyRouteDefinition::class,
            static fn (): WebhookProxyRouteDefinition => new class implements WebhookProxyRouteDefinition {
                public function uri(): string
                {
                    return '/webhooks/proxy/{endpointKey}/{tenant?}';
                }
            },
        );
    }

    protected function defineRoutes($router): void
    {
        Route::get('/sentinel', static fn (): string => 'sentinel');
        Route::webhooks('/{tenant?}')->proxy();
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
