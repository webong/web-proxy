<?php

declare(strict_types=1);

use Webong\WebProxy\WebhookPathTemplates;

beforeEach(function (): void {
    config()->set('webhook-client.configs', [
        [
            'name' => 'telegram',
            'path_template' => '/webhooks/telegram/{tenant?}',
        ],
    ]);
});

it('resolves optional owner placeholders from the opaque owner key', function (): void {
    expect(app(WebhookPathTemplates::class)->forOwner('tenant-123'))
        ->toMatchArray([
            'telegram' => '/webhooks/telegram/tenant-123',
        ]);
});

it('removes optional owner placeholders when no owner is active', function (): void {
    expect(app(WebhookPathTemplates::class)->forOwner(null))
        ->toMatchArray([
            'telegram' => '/webhooks/telegram',
        ]);
});
