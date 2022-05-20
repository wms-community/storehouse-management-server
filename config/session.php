<?php
// +----------------------------------------------------------------------
// | Session settings
// +----------------------------------------------------------------------

return [
    // session name
    'name'           => 'PHPSESSID',
    // SESSION_ ID submission variable to solve the cross domain problem of flash upload
    'var_session_id' => '',
    // The drive mode supports file cache
    'type'           => 'file',
    // The storage connection ID is valid when type uses cache
    'store'          => null,
    // Expiration time
    'expire'         => 1440,
    // prefix
    'prefix'         => '',
];
