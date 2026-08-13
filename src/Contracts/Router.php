<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Contracts;

use Zorvia\WebProxy\Models\WebProxyCall;
use Zorvia\WebProxy\WebhookRoute;

interface Router
{
    /** @return iterable<WebhookRoute> */
    public function routes(WebProxyCall $webhookCall): iterable;

}
