<?php

return [
    'default'         => env('database.driver', 'mysql'),
    'time_query_rule' => [],
    'auto_timestamp'  => true,
    'datetime_format' => 'Y-m-d H:i:s',
    'datetime_field'  => '',
    'connections'     => [
        'mysql' => [
            'type'            => env('database.type', 'mysql'),
            'hostname'        => env('database.hostname', '127.0.0.1'),
            'database'        => env('database.database', 'database'),
            'username'        => env('database.username', 'username'),
            'password'        => env('database.password', 'password'),
            'hostport'        => env('database.hostport', '3306'),
            'params'          => [],
            'charset'         => env('database.charset', 'utf8'),
            'prefix'          => env('database.prefix', 'kfgl_'),
            'deploy'          => 0,
            'rw_separate'     => false,
            'master_num'      => 1,
            'slave_no'        => '',
            'fields_strict'   => true,
            'break_reconnect' => true,
            'trigger_sql'     => env('app_debug', false),
            'fields_cache'    => false,
        ],
    ],
];
