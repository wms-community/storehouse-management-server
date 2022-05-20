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
    // 允许的语言列表
    'allow_lang_list' => ['zh-cn', 'en-us'],
    // 多语言自动侦测变量名
    'detect_var'      => 'lang',
    // 是否使用Cookie记录
    'use_cookie'      => true,
    // 多语言cookie变量
    'cookie_var'      => 'think_lang',
    // 多语言header变量
    'header_var'      => 'think-lang',
    // 扩展语言包
    'extend_list'     => [],
    // Accept-Language转义为对应语言包名称
    'accept_language' => [
        'zh-hans-cn' => 'zh-cn',
    ],
    // 是否支持语言分组
    'allow_group'     => true,
];
