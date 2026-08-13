<?php

declare(strict_types=1);

namespace Webong\WebProxy\Contracts;

use Webong\WebProxy\Models\WebProxyCall;
use Webong\WebProxy\WebhookRoute;

interface Router
{
    /** @return iterable<WebhookRoute> */
    public function routes(WebProxyCall $webhookCall): iterable;

}
