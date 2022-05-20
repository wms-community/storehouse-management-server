<?php
// +----------------------------------------------------------------------
// | Template settings
// +----------------------------------------------------------------------

return [
    // The template engine type uses think
    'type'          => 'Think',
    // The default template rendering rule 1 resolves to lowercase + underline 2 converts all lowercase 3 maintains the operation method
    'auto_rule'     => 1,
    // Template directory name
    'view_dir_name' => 'view',
    // Template suffix
    'view_suffix'   => 'html',
    // Template file name separator
    'view_depr'     => DIRECTORY_SEPARATOR,
    // Template engine normal tag start tag
    'tpl_begin'     => '{',
    // Template engine normal tag end tag
    'tpl_end'       => '}',
    // Tag library tag start tag
    'taglib_begin'  => '{',
    // Tag library tag end tag
    'taglib_end'    => '}',
];
