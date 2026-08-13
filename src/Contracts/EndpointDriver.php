<?php

declare(strict_types=1);

namespace Webong\WebProxy\Contracts;

use Webong\WebProxy\EndpointDefinition;
use Webong\WebProxy\ProvisionedEndpoint;

interface EndpointDriver
{
    public function provision(EndpointDefinition $definition): ProvisionedEndpoint;

    public static function getIdentifier(): string;

    public function supports(string $driver): bool;
}
