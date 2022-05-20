<?php

// +----------------------------------------------------------------------
// | Cache settings
// +----------------------------------------------------------------------

return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb"
    |
    */
    'default' => env('cache.driver', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */
    'stores'  => [
        'file' => [
            // Driving mode
            'type'       => env('cache.mode', 'File'),
            // Cache save directory
            'path'       => env('cache.path', ''),
            // Cache prefix
            'prefix'     => env('cache.prefix', ''),
            // Cache validity period 0 indicates permanent cache
            'expire'     => env('cache.expire', 0),
            // Cache label prefix
            'tag_prefix' => env('cache.tag', 'tag:'),
            // Serialization mechanism, such as ['serialize ',' unserialize ']
            'serialize'  => [],
        ],
        // More cache connections
    ],
];
