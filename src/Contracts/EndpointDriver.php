<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Contracts;

use Zorvia\WebProxy\EndpointDefinition;
use Zorvia\WebProxy\ProvisionedEndpoint;

interface EndpointDriver
{
    public function provision(EndpointDefinition $definition): ProvisionedEndpoint;

    public static function getIdentifier(): string;

    public function supports(string $driver): bool;
}
