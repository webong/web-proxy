<?php

declare(strict_types=1);

namespace Zorvia\WebProxy;

final readonly class WebhookBaseUrlResolver
{
    public function resolve(mixed ...$candidates): string
    {
        foreach ([
            ...$candidates,
            config('web-proxy.base_url'),
        ] as $candidate) {
            if (is_string($candidate) && mb_trim($candidate) !== '') {
                return mb_rtrim($candidate, '/');
            }
        }

        return '';
    }
}
