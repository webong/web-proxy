<?php

declare(strict_types=1);

namespace Webong\WebProxy\Tests\Support;

use Illuminate\Foundation\Queue\Queueable;
use Webong\WebProxy\Contracts\WebhookJob;
use Webong\WebProxy\WebhookProxyDelivery;

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
