<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Contracts;

use Zorvia\WebProxy\WebhookProxyDelivery;

interface WebhookEvent
{
    public static function fromWebhookProxy(WebhookProxyDelivery $delivery): static;
}
