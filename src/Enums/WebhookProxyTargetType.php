<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Enums;

enum WebhookProxyTargetType: string
{
    case REQUEST = 'request';
    case EVENT = 'event';
    case JOB = 'job';
}
