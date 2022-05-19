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
        'https://dev.azure.com/blessing-skin/51010f6d-9f99-40f1-a262-0a67f788df32/_apis/git/'.
        'repositories/a9ff8df7-6dc3-4ff8-bb22-4871d3a43936/Items?path=%2Fupdate.json'
    ),
    
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
