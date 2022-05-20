<?php
// +----------------------------------------------------------------------
// | Multilingual Settings
// +----------------------------------------------------------------------

return [
    /*
    |--------------------------------------------------------------------------
    | Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by locals.
    |
    */
    'default_lang'    => env('lang.default_lang', 'zh-cn'),
    
    // List of allowed languages
    'allow_lang_list' => ['zh-cn', 'en-us'],
    // Multilingual automatic detection of variable names
    'detect_var'      => 'lang',
    // Whether to use cookie record
    'use_cookie'      => true,
    // Multilingual cookie variable
    'cookie_var'      => 'think_lang',
    // Multilingual header variable
    'header_var'      => 'think-lang',
    // Extended language pack
    'extend_list'     => [],
    // Escape Accept-Language to the corresponding language pack name
    'accept_language' => [
        'zh-hans-cn' => 'zh-cn',
    ],
    // Is language grouping supported
    'allow_group'     => true,
];
