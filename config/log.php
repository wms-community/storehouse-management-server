<?php

// +----------------------------------------------------------------------
// | log setting
// +----------------------------------------------------------------------
return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */
    'default'      => env('log.channel', 'file'),
  
    // Logging level
    'level'        => [],
    // Channel of log type record ['error '= >' email ',...]
    'type_channel' => [],
    // Turn off global log writing
    'close'        => false,
    // Global log processing supports closures
    'processor'    => null,

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "file",
    |
    */
    'channels'     => [
        'file' => [
            // Logging method
            'type'           => 'File',
            // Log save directory
            'path'           => '',
            // Single file log write
            'single'         => true,
            // Independent log level
            'apart_level'    => [],
            // Maximum number of log files
            'max_files'      => 3,
            // Record in JSON format
            'json'           => false,
            // Log processing
            'processor'      => null,
            // Turn off channel log writing
            'close'          => false,
            // Log output formatting
            'format'         => '[%s][%s] %s',
            // Whether to write in real time
            'realtime_write' => false,
        ],
        // Other log channel configurations
    ],

];
