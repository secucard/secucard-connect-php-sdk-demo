<?php

/**
 * THIS IS SAMPLE command line script to simulate event push from backend
 * To use it, update @variables with correct values and run command "php push.php"
 *
 * The script registers callback handler for event on SecucardConnect object, then
 * it creates its own sample $raw_event_data (normally the event data would come from the server to url_push)
 * and it calls the processPush() method on SecucardConnect object that processes the pushed data and calls the registered handler with the data of changed object
 *
 */

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

// import the vendor autoload
require_once __DIR__ . '/../shared/init.php';

$config = [
    // demo server
    'base_url' => 'https://connect-dist.secupay-ag.de',
    'debug' => true
];

$logger = new Logger(null, true);

$store = new DummyStorage();

// payment product always uses client_credentials
$cred = new ClientCredentials('@your-client-id', '@your-client-secret');

// initialize the client
$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);

// Register function to handle new/changed objects
$secucard->payment->secupaydebits->onSecupayDebitChanged(function ($obj) {
    var_dump($obj);
});

// simulate sample push data
// you should set correct target and object and id fields
$raw_event_data = '{
    "object":"event.pushes",
    "id":"EVT_123456789",
    "target":"payment.secupaydebits",
    "type":"changed",
    "data":[
        {
            "id":"<your-ident-request-id>"
        }
    ]
}';

// if the data would be posted to the current script (depends on your url_push configuration for the contract), you can use following code to get posted data
//$raw_event_data = file_get_contents("php://input");

try {
    $secucard->handleEvent($raw_event_data);
} catch (Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

// sleep before exit to give the event handler time to finish
sleep(10);
