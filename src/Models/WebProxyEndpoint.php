<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property array<string, mixed>|null $metadata
 */
class WebProxyEndpoint extends Model
{
    use HasUuids;

    public const string REGISTRY_METADATA_KEY = '_registry';

    protected $table = 'web_proxy_endpoints';

    protected $fillable = [
        'client',
        'external_id',
        'endpoint_key',
        'signing_secret',
        'verification_token',
        'credential_owner_id',
        'metadata',
        'is_managed',
        'is_active',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'signing_secret' => 'encrypted',
            'verification_token' => 'encrypted',
            'metadata' => 'array',
            'is_managed' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<WebProxyDestination, $this> */
    public function destinations(): HasMany
    {
        return $this->hasMany(WebProxyDestination::class, 'endpoint_id');
    }
}
