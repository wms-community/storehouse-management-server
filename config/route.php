<?php
// +----------------------------------------------------------------------
// | Routing settings
// +----------------------------------------------------------------------

return [
    // Pathinfo separator
    'pathinfo_depr'         => '/',
    // URL pseudo static suffix
    'url_html_suffix'       => 'html',
    // The URL normal mode parameter is used for automatic generation
    'url_common_param'      => true,
    // Enable route delay resolution
    'url_lazy_route'        => true,
    // Force routing
    'url_route_must'        => true,
    // Merge routing rules
    'route_rule_merge'      => false,
    // Is the route exactly matched
    'route_complete_match'  => false,
    // Access controller layer name
    'controller_layer'      => 'controller',
    // Empty controller name
    'empty_controller'      => 'Error',
    // Use controller suffix
    'controller_suffix'     => false,
    // Default routing variable rule
    'default_route_pattern' => '[\w\.]+',
    // Whether to enable request caching. True automatic caching supports setting request caching rules
    'request_cache_key'     => false,
    // Request cache validity
    'request_cache_expire'  => null,
    // Global request cache exclusion rule
    'request_cache_except'  => [],
    // Default controller name
    'default_controller'    => 'Index',
    // Default action name
    'default_action'        => 'index',
    // Operation method suffix
    'action_suffix'         => '',
    // Processing method returned in default jsonp format
    'default_jsonp_handler' => 'jsonpReturn',
    // Default jsonp processing method
    'var_jsonp_handler'     => 'callback',
];
