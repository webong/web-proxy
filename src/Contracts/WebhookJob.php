<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Contracts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Zorvia\WebProxy\WebhookProxyDelivery;

interface WebhookJob extends ShouldQueue
{
    public static function fromWebhookProxy(WebhookProxyDelivery $delivery): static;
}
