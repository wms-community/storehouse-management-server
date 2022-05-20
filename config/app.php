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
    'version' => '0.0.1-alpha-202205190805',
    
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
        'https://comming.soon/update.json'
    ),
    
    'name' => env('APP_NAME', 'storehouse_management'),
    
    /*
    |--------------------------------------------------------------------------
    | Application Host
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */
    'app_host'         => env('app.host', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Application Namespace
    |--------------------------------------------------------------------------
    |
    | Applied namespace
    |
    */
    'app_namespace'    => '',
    
    /*
    |--------------------------------------------------------------------------
    | With route
    |--------------------------------------------------------------------------
    |
    | Route in the Application.
    |
    */
    'with_route'       => true,
    
    /*
    |--------------------------------------------------------------------------
    | Default Application
    |--------------------------------------------------------------------------
    |
    | The default Application.
    |
    */
    'default_app'      => 'index',
    
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
    'default_timezone' => 'Asia/Shanghai',
    
    /*
    |--------------------------------------------------------------------------
    | Application Map
    |--------------------------------------------------------------------------
    |
    | Automatic multi application mode is effective.
    |
    */
    'app_map'          => [],
    
    /*
    |--------------------------------------------------------------------------
    | Bind domain
    |--------------------------------------------------------------------------
    |
    | Automatic multi application mode is effective.
    |
    */
    'domain_bind'      => [],
    
    /*
    |--------------------------------------------------------------------------
    | List of apps with URL access disabled
    |--------------------------------------------------------------------------
    |
    | Automatic multi application mode is effective.
    |
    */
    'deny_app_list'    => [],
    
    /*
    |--------------------------------------------------------------------------
    | Template file for exception page
    |--------------------------------------------------------------------------
    |
    | It will display an error message when there is a problem with the system.
    |
    */
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',
    
    /*
    |--------------------------------------------------------------------------
    | Error display message
    |--------------------------------------------------------------------------
    |
    | Non debug mode is valid.
    |
    */
    'error_message'    => 'System Error',
    
    /*
    |--------------------------------------------------------------------------
    | Display error message
    |--------------------------------------------------------------------------
    |
    | Display error message.
    |
    */
    'show_error_msg'   => false,
];
