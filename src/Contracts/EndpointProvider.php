<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Contracts;

use Illuminate\Support\Collection;
use Zorvia\WebProxy\Endpoint;
use Zorvia\WebProxy\EndpointDefinition;
use Zorvia\WebProxy\DestinationDefinition;
use Zorvia\WebProxy\DestinationRecord;
use Zorvia\WebProxy\EndpointRecord;
use Zorvia\WebProxy\ProvisionedEndpoint;
use Zorvia\WebProxy\WebhookRoute;

interface EndpointProvider extends ExecutionContext
{
    public function define(
        EndpointDefinition $definition,
        ProvisionedEndpoint $provisionedEndpoint,
    ): Endpoint;

    public function find(EndpointDefinition $definition): ?Endpoint;

    public function resolveByKey(string $endpointKey): ?Endpoint;

    public function resolveById(string $id): ?Endpoint;

    public function findManaged(string $client, string $externalId): ?EndpointRecord;

    public function register(EndpointRecord $record, string $registry): void;

    public function registryForKey(string $endpointKey): ?string;

    public function registryForId(string $id): ?string;

    public function attach(EndpointRecord $endpoint, DestinationDefinition $definition): DestinationRecord;

    /** @return Collection<int, DestinationRecord> */
    public function destinationsFor(EndpointRecord $endpoint, WebhookRoute $route): Collection;

    public function destinationById(string $id): ?DestinationRecord;

    /** @param array<string, mixed> $attributes */
    public function updateDestination(DestinationRecord $destination, array $attributes): void;

    /** @param array<string, mixed> $registration */
    public function updateEndpoint(EndpointRecord $endpoint, array $registration): EndpointRecord;

    public function deactivateDestinations(
        string $ownerId,
        string $registrationId,
        string $webhookGroup,
    ): int;

    public function hasActiveDestination(
        string $ownerId,
        string $registrationId,
        string $webhookGroup,
    ): bool;
}
