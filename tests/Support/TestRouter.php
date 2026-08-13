<?php

declare(strict_types=1);

namespace Webong\WebProxy\Tests\Support;

use Webong\WebProxy\Contracts\Router;
use Webong\WebProxy\Models\WebProxyCall;
use Webong\WebProxy\WebhookRoute;

final class TestRouter implements Router
{
    /** @return iterable<WebhookRoute> */
    public function routes(WebProxyCall $webhookCall): iterable
    {
        return [];
    }

}
