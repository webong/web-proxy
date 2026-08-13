<?php

declare(strict_types=1);

namespace Webong\WebProxy\Contracts;

use Webong\WebProxy\WebhookExecutionContextPayload;

/**
 * Resolves the webhook proxy context for the current tenancy/authority on
 * demand. Integrators implement this contract using their own routing
 * primitives, keeping the web proxy package context agnostic.
 */
interface WebhookContextProvider
{
    /**
     * Resolve the webhook context payload for the active tenancy.
     */
    public function resolve(): WebhookExecutionContextPayload;
}
