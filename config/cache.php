<?php

// +----------------------------------------------------------------------
// | Cache settings
// +----------------------------------------------------------------------

return [
    // Default cache driver
    'default' => env('cache.driver', 'file'),

    // Cache connection mode configuration
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
