<?php

declare(strict_types=1);

use Zorvia\WebProxy\WebhookTargetRegistry;

it('reads targets from the generated discovery cache shape', function (): void {
    $registry = app(WebhookTargetRegistry::class);

    expect($registry->all()['job'])
        ->toHaveKey('test-webhook-job', Zorvia\WebProxy\Tests\Support\TestWebhookJob::class);
});
