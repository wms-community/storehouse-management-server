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
            'type'       => 'File',
            // Cache save directory
            'path'       => '',
            // Cache prefix
            'prefix'     => '',
            // Cache validity period 0 indicates permanent cache
            'expire'     => 0,
            // Cache label prefix
            'tag_prefix' => 'tag:',
            // Serialization mechanism, such as ['serialize ',' unserialize ']
            'serialize'  => [],
        ],
        // More cache connections
    ],
];
