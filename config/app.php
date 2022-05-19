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
    'version' => '0.0.1-alpha-202205190757',
    
    /*
    |--------------------------------------------------------------------------
    | Update Source
    |--------------------------------------------------------------------------
    |
    | Where to get information of new versions.
    |
    */
    'update_source' => env(
        'UPDATE_SOURCE',
        'Comming soon...'
    ),
    
    'name' => env('APP_NAME', 'storehouse_management'),
    
    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */
    'env' => env('APP_ENV', 'production'),
    
    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */
    'debug' => (bool) env('APP_DEBUG', false),
    
    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */
    'timezone' => env('Asia/Shanghai'),
    
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
