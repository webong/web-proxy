<?php

declare(strict_types=1);

namespace Zorvia\WebProxy;

use Zorvia\WebProxy\Contracts\EndpointRegistrar;

final readonly class ResolvedEndpoint
{
    public function __construct(
        public string $registry,
        public EndpointRegistrar $registrar,
        public Endpoint $endpoint,
    ) {
    }
}
