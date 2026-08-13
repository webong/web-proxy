<?php

declare(strict_types=1);

namespace Webong\WebProxy\Contracts;

use Webong\WebProxy\WebhookProxyDelivery;

interface WebhookEvent
{
    public static function fromWebhookProxy(WebhookProxyDelivery $delivery): static;
}
