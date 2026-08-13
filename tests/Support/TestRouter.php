<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Tests\Support;

use Zorvia\WebProxy\Contracts\Router;
use Zorvia\WebProxy\Models\WebProxyCall;
use Zorvia\WebProxy\WebhookRoute;

final class TestRouter implements Router
{
    /** @return iterable<WebhookRoute> */
    public function routes(WebProxyCall $webhookCall): iterable
    {
        return [];
    }

}
