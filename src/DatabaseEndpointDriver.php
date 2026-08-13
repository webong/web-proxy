<?php

declare(strict_types=1);

namespace Zorvia\WebProxy;

use Zorvia\WebProxy\Contracts\EndpointDriver;

final class DatabaseEndpointDriver implements EndpointDriver
{
    public function provision(EndpointDefinition $definition): ProvisionedEndpoint
    {
        return new ProvisionedEndpoint(endpointKey: $definition->endpointKey);
    }

    public static function getIdentifier(): string
    {
        return 'database';
    }

    public function supports(string $driver): bool
    {
        return true;
    }
}
