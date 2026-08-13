<?php

declare(strict_types=1);

namespace Webong\WebProxy\Tests\Support;

use Webong\WebProxy\Contracts\WebhookContextProvider;
use Webong\WebProxy\WebhookExecutionContextPayload;

final class TestWebhookContextProvider implements WebhookContextProvider
{
    public function resolve(): WebhookExecutionContextPayload
    {
        return new WebhookExecutionContextPayload(
            ownerId: 'owner-123',
            baseUrl: 'https://proxy.example.test',
            pathTemplates: ['test-router' => '/webhooks/test-router/owner-123'],
            routeParameters: ['scope' => 'owner-123'],
            routeContext: 'owner-123',
        );
    }
}
