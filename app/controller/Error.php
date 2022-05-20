<?php
namespace app\controller;

// System error file

class Error 
{
    public function __call($method, $args)
    {
        return '<title>Error</title><style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #4a86e8; color: #fff;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:(</h1><p>Error<br/></p><span style="font-size: 25px">Oh,No. System error.</span><br></div>';
    }
}
