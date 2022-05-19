<?php
if (PHP_VERSION_ID < 70205) {
    echo '<title>PHP version is too low</title><style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #4a86e8; color: #fff;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:(</h1><p>PHP version is too low<br/></p><span style="font-size: 25px">The website is blocked by us because the PHP version is too low. If you are a webmaster, please update your PHP version to PHP 7.2.5 or above.</span><br><small>Error message:Your PHP version is: '.PHP_VERSION.', lower than the minimum PHP version PHP 7.2.5</small><br><small>Error type: PHP error</small><br><small>Error code: PHP_VITL</small></div>'.PHP_EOL;
    exit(1);
}
require_once __DIR__ . '/composer/autoload_real.php';
return ComposerAutoloaderInit31f8b83dc8a4563f9cdac60566ac4fa2::getLoader();
