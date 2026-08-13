<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Events;

use Zorvia\WebProxy\WebhookProxyDelivery;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final readonly class WebhookDeliverySucceeded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public WebhookProxyDelivery $delivery,
        public string $target,
        public int $responseStatus,
    ) {
    }
}
