<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('WEB_PROXY_URL'),

    'secret' => env('WEB_PROXY_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Context Provider
    |--------------------------------------------------------------------------
    |
    | Applications may provide a context provider for tenant, authority, or
    | request-specific webhook routing. The package remains context-agnostic.
    |
    */
    'context_provider' => null,

    'container' => [
        'web_proxy' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routers
    |--------------------------------------------------------------------------
    |
    | Maps webhook-client names to router classes. Applications may generate
    | this section from #[WebhookRouter] attributes with the discovery command.
    |
    */
    'routers' => [],


    /*
    |--------------------------------------------------------------------------
    | Targets
    |--------------------------------------------------------------------------
    |
    | Maps target types and keys to target classes. Applications may generate
    | this section from #[WebhookTarget] attributes with the discovery command.
    |
    */

    'targets' => [
        'job' => [

        ],
        'event' => [

        ],
    ],

    'discovery' => [
        'scan_on_boot' => (bool) env('WEB_PROXY_DISCOVER_ON_BOOT', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    |
    | The default channel (protocol / transport engine) and registry
    | (storage / endpoint routing) selected when none is explicitly given.
    |
    */
    'defaults' => [
        'channel' => env('WEB_PROXY_CHANNEL', 'default'),
        'registry' => env('WEB_PROXY_REGISTRY', 'local'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Channel Plane (Protocol / Transport Engine)
    |--------------------------------------------------------------------------
    |
    | A channel is the protocol over which events are received and
    | dispatched — e.g. a "webhook" channel (built on spatie
    | webhook-client) or a future "websocket" channel.
    |
    */
    'channels' => [
        [
            'name' => 'default',
            'driver' => 'webhook',
            'path' => '/',
            'methods' => ['GET', 'POST'],
            'client' => [
                'store_headers' => '*',
            ],
        ],

        /*
         | Additional channels can be registered here, e.g.:
         |
         | [
         |     'name' => 'socket',
         |     'driver' => 'websocket',
         |     'path' => 'socket/',
         |     'methods' => ['GET', 'POST'],
         | ],
        */
    ],

    /*
    |--------------------------------------------------------------------------
    | Registry Plane (Storage / Endpoint Routing)
    |--------------------------------------------------------------------------
    |
    | A registry composes an ingress driver with a storage provider. The driver
    | provisions the public endpoint; the provider owns endpoint bindings and
    | destination lookup.
    |
    | The default "local" registry uses the built-in "database" driver
    | (EnsureEndpoint storage). Other registries may reuse the same driver
    | with different options, or use another driver entirely.
    |
    */
    'registries' => [
        'local' => [
            'driver' => 'database',
            'provider' => 'database',
        ],

        /*
         | The built-in database driver provisions an application-owned
         | endpoint. Applications may provide another EndpointDriver class.
        */
    ],

    'providers' => [
        'database' => [
            'driver' => 'database',
        ],
    ],

    'signature_tolerance' => (int) env('WEB_PROXY_SIGNATURE_TOLERANCE', 300),

    'connect_timeout' => (int) env('WEB_PROXY_CONNECT_TIMEOUT', 5),

    'timeout' => (int) env('WEB_PROXY_TIMEOUT', 15),

    'job_timeout' => (int) env('WEB_PROXY_JOB_TIMEOUT', 60),

    'idempotency_ttl' => (int) env('WEB_PROXY_IDEMPOTENCY_TTL', 86400),

    'queue_name' => env('WEB_PROXY_QUEUE'),

    'tries' => (int) env('WEB_PROXY_TRIES', 3),

    'backoff' => array_values(
        array_filter(
            array_map('intval', explode(',', (string) env('WEB_PROXY_BACKOFF_SECONDS', ''))),
            static fn (int $seconds): bool => $seconds > 0,
        ),
    ),

    'log_failures' => (bool) env('WEB_PROXY_LOG_FAILURES', true),

];
