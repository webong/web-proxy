<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Tests\Support;

use Illuminate\Foundation\Queue\Queueable;
use Zorvia\WebProxy\Contracts\WebhookJob;
use Zorvia\WebProxy\WebhookProxyDelivery;

final class TestWebhookJob implements WebhookJob
{
    use Queueable;

    public function __construct(public readonly WebhookProxyDelivery $delivery)
    {
    }

    public static function fromWebhookProxy(WebhookProxyDelivery $delivery): static
    {
        return new self($delivery);
    }

    public function handle(): void
    {
    }
}
