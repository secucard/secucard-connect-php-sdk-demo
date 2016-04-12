<?php

/**
 * THIS IS SAMPLE command line script to create payment.containers object
 * To use it, update @variables with correct values and run command "php container.php"
 */

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Payment\Model\Container;
use SecucardConnect\Product\Payment\Model\Data;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

require_once __DIR__ . '/../shared/init.php';

$config = [
    // demo server
    'base_url' => 'https://connect-dist.secupay-ag.de',
    // live
    //'base_url' => 'https://connect.secucard.com',
    'debug' => true
];

$logger = new Logger(fopen("php://stdout", "a"), true);

$store = new DummyStorage();

// payment product uses client_credentials auth so either provide valid refresh token here or obtain token by processing
// the auth flow, see \SecucardConnect\Auth\ClientCredentials
$cred = new ClientCredentials('@your-client-id', '@your-client-secret');

$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);

$service = $secucard->payment->containers;

// You may obtain a global list of available containers
$containers = $service->getList();
if ($containers === null) {
    throw new Exception("No Containers found."); // Should not happen
}


// new container creation:

// create new Data subobject for contrainer
$container_data = new Data();
$container_data->iban = '@container-iban';
$container_data->owner = '@account-owner-name';

$container = new Container();
$container->private = $container_data;
$logger->debug('object data initialized');

try {
    $container = $service->save($container);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($container->id) {
    echo 'Created Container with id: ' . $container->id . "\n";
    echo 'Container data: ' . print_r($container, true) . "\n";
} else {
    echo 'Container creation failed';
}
