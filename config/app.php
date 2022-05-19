<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | Version of Storehouse Management Server.
    |
    */
    'version' => '6.0.0-rc.2',
    'app_host'         => env('app.host', ''),
    'app_namespace'    => '',
    'with_route'       => true,
    'default_app'      => 'index',
    'default_timezone' => 'Asia/Shanghai',
    'app_map'          => [],
    'domain_bind'      => [],
    'deny_app_list'    => [],
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',
    'error_message'    => 'System Error',
    'show_error_msg'   => false,
];
