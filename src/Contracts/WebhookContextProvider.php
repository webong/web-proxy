<?php

declare(strict_types=1);

namespace Webong\WebProxy\Contracts;

use Webong\WebProxy\WebhookExecutionContextPayload;

/**
 * Resolves the webhook proxy context for the current tenancy/authority on
 * demand. The application implements this contract using its own tenancy
 * primitives, keeping the web proxy package agnostic of the host application.
 */
interface WebhookContextProvider
{
    /**
     * Resolve the webhook context payload for the active tenancy.
     */
    public function resolve(): WebhookExecutionContextPayload;
}
