<?php
use app\ExceptionHandle;
use app\Request;

// Container provider definition file
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
];
