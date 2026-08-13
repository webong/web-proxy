<?php

declare(strict_types=1);

namespace Webong\WebProxy\Tests\Support;

use Illuminate\Support\Str;
use Webong\WebProxy\DestinationDefinition;
use Webong\WebProxy\DestinationRecord;
use Webong\WebProxy\Endpoint;
use Webong\WebProxy\EndpointDefinition;
use Webong\WebProxy\EnsureEndpoint;

final class WebhookFixtures
{
    public static function endpoint(
        string $externalId = 'source-1',
        string $ownerId = 'owner-1',
        ?string $endpointKey = null,
    ): Endpoint {
        return app(EnsureEndpoint::class)->handle(new EndpointDefinition(
            client: 'test-router',
            externalId: $externalId,
            signingSecret: 'source-secret',
            verificationToken: 'verify-token',
            endpointKey: $endpointKey,
            credentialOwnerId: $ownerId,
            managed: false,
        ));
    }

    /** @param array<string, mixed> $metadata */
    public static function destination(
        Endpoint $endpoint,
        string $ownerId = 'owner-1',
        ?string $registrationId = null,
        string $webhookGroup = 'example-events',
        string $routingScope = 'account',
        string $routingKey = 'account-1',
        string $target = 'https://receiver.example.test/webhooks/example',
        array $metadata = [],
        bool $allowsMultipleSubscribers = false,
    ): DestinationRecord {
        return $endpoint->attach(new DestinationDefinition(
            ownerId: $ownerId,
            registrationId: $registrationId ?? (string) Str::uuid(),
            webhookGroup: $webhookGroup,
            routingScope: $routingScope,
            routingKey: $routingKey,
            target: $target,
            metadata: $metadata,
            allowsMultipleSubscribers: $allowsMultipleSubscribers,
        ));
    }
}
