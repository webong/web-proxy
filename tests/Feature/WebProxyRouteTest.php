<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\WebhookClient\Http\Controllers\WebhookController;

it('registers the root webhook channel at the canonical proxy path', function (): void {
    $route = Route::getRoutes()->match(Request::create(
        '/webhooks/proxy/example-endpoint',
        'POST',
    ));

    expect(mb_ltrim($route->getActionName(), '\\'))->toBe(WebhookController::class)
        ->and($route->uri())->toBe('webhooks/proxy/{endpointKey}/{tenant?}');
});

it('keeps the endpoint context path optional', function (): void {
    expect(Route::getRoutes()->match(Request::create(
        '/webhooks/proxy/example-endpoint',
        'POST',
    ))->uri())->toBe('webhooks/proxy/{endpointKey}/{tenant?}')
        ->and(Route::getRoutes()->match(Request::create(
            '/webhooks/proxy/example-endpoint/context-123',
            'POST',
        ))->uri())->toBe('webhooks/proxy/{endpointKey}/{tenant?}');
});

it('does not let the root proxy shorthand capture unrelated routes', function (): void {
    $route = Route::getRoutes()->match(Request::create('/sentinel', 'GET'));

    expect($route->uri())->toBe('sentinel');
});
