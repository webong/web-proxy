<?php

declare(strict_types=1);

namespace Webong\WebProxy;

use Webong\WebProxy\Contracts\WebhookContextProvider;
use Closure;
use Illuminate\Support\Facades\Context;

/**
 * Resolves the webhook proxy context for the active tenancy/authority on
 * demand through the registered WebhookContextProvider, scoping callbacks
 * with the resolved hidden context.
 */
final class WebhookContext
{
    public function __construct(
        private readonly WebhookContextProvider $provider,
    ) {
    }

    /**
     * Resolve the webhook context payload for the active tenancy.
     */
    public function payload(): WebhookExecutionContextPayload
    {
        $hidden = Context::getHidden(WebhookExecutionContextPayload::class);

        if ($hidden instanceof WebhookExecutionContextPayload) {
            return $hidden;
        }

        return $this->provider->resolve();
    }

    /**
     * Capture the hidden context array for the active tenancy.
     *
     * @return array<string, mixed>
     */
    public function capture(): array
    {
        return $this->captureFrom($this->payload());
    }

    /**
     * Persist the resolved context into Laravel's hidden context for the
     * current request/tenancy scope.
     *
     * Re-resolves the context from the provider so repeated bootstraps reflect
     * the latest configuration, rather than reusing a previously captured
     * payload.
     */
    public function hydrate(): void
    {
        Context::addHidden($this->captureFrom($this->provider->resolve()));
    }

    /**
     * Build the hidden context array from a resolved payload.
     *
     * @return array<string, mixed>
     */
    private function captureFrom(WebhookExecutionContextPayload $payload): array
    {
        return [
            WebhookContextKeys::BASE_URL->value => $payload->baseUrl,
            WebhookContextKeys::PATH_TEMPLATES->value => $payload->pathTemplates,
            WebhookProxyRouteDefinition::CONTEXT_KEY => $payload->routeContext,
            WebhookExecutionContextPayload::class => $payload,
            WebhookDestinationOwner::CONTEXT_KEY => $payload->ownerId,
        ];
    }

    /**
     * Remove the resolved context keys from Laravel's hidden context.
     */
    public function forget(): void
    {
        $capture = $this->capture();

        foreach (array_keys($capture) as $key) {
            Context::forgetHidden($key);
        }
    }

    /**
     * Run a callback within the resolved hidden context.
     *
     * @template TResult
     *
     * @param  Closure(): TResult  $callback
     * @return TResult
     */
    public function scope(Closure $callback): mixed
    {
        return Context::scope(
            callback: $callback,
            hidden: $this->capture(),
        );
    }
}
