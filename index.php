<?php
// +----------------------------------------------------------------------
// | Storehouse Management Server
// +----------------------------------------------------------------------
// | Copyright (c) 2022 SANYIMOE Inc All. rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: MoeCinnamo <abcd2890000456@gmail.com>
// +----------------------------------------------------------------------

// [ Application entry file ]
namespace think;
require __DIR__ . '/vendor/autoload.php';
// Execute HTTP application and respond
$http = (new App())->http;
$response = $http->run();
$response->send();
$http->end($response);
