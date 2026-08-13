<?php

declare(strict_types=1);

namespace Zorvia\WebProxy;

final readonly class ProvisionedEndpoint
{
    /** @param array<string, mixed> $metadata */
    public function __construct(
        public ?string $endpointKey = null,
        public ?string $callbackUrl = null,
        public array $metadata = [],
    ) {
    }
}
