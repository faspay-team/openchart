<?php

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    throw new Exception('PHP version >= 5.3.0 required');
}

if (!function_exists('curl_init')) {
    throw new Exception('Faspay needs the CURL PHP extension.');
}

$path = dirname(__FILE__).'/';

require $path."Faspay/Config.php";
require $path."Faspay/Transaction.php";
require $path."Faspay/Api.php";
require $path."Faspay/Utils.php";

require $path."Faspay/FaspayNotification.php";
require $path."Faspay/FaspayBussiness.php";


?>