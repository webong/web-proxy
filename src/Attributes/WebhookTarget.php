<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Attributes;

use Attribute;
use Zorvia\WebProxy\Enums\WebhookProxyTargetType;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class WebhookTarget
{
    public function __construct(
        public readonly string $key,
        public readonly ?WebhookProxyTargetType $type = null,
    )
    {
    }
}
