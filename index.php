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

// [ 应用入口文件 ]
namespace think;
require __DIR__ . '/vendor/autoload.php';
// 执行HTTP应用并响应
$http = (new App())->http;
$response = $http->run();
$response->send();
$http->end($response);
