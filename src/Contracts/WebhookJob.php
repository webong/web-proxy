<?php

declare(strict_types=1);

namespace Webong\WebProxy\Contracts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Webong\WebProxy\WebhookProxyDelivery;

interface WebhookJob extends ShouldQueue
{
    public static function fromWebhookProxy(WebhookProxyDelivery $delivery): static;
}
