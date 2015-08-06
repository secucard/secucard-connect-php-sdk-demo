<?php

require_once __DIR__ . "/../../vendor/autoload.php";

$config = array(
    'client_id'=>'XXXX',
    'client_secret'=>'XXXXXXX',
);

// Dummy Log
$fp = fopen("/tmp/secucard_php_test.log", "a");
$logger = new secucard\client\log\Logger($fp, true);

// create client
$secucard = new secucard\Client($config, $logger);
