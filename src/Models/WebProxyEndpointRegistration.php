<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Maps an endpoint's public routing key to the registry that owns its endpoint
 * record.
 */
class WebProxyEndpointRegistration extends Model
{
    use HasUuids;

    protected $table = 'web_proxy_endpoint_registrations';

    protected $fillable = [
        'owner_id',
        'endpoint_id',
        'endpoint_key',
        'client',
        'registry',
        'registry_id',
        'callback_url',
        'metadata',
        'is_active',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
