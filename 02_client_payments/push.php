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
use SecucardConnect\Product\Common\Model\BaseModel;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;


// import the vendor autoload
require_once __DIR__ . '/../shared/init.php';

$config = [
    // demo server
    'base_url' => 'https://connect-dist.secupay-ag.de',
    'debug' => true
];

$logger = new Logger(fopen("php://stdout", "a"), true);

$store = new DummyStorage();

// payment product uses client_credentials auth so either provide valid refresh token here or obtain token by processing
// the auth flow, see \SecucardConnect\Auth\ClientCredentials
$cred = new ClientCredentials('@your-client-id', '@your-client-secret');

// initialize the client
$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);


/**
 * Function that will handle the push
 * @param BaseModel $obj - the event object (with latest data renewed by sdk)
 */
function gotObjectPush(BaseModel $obj) {
    // do something with updated object
    var_dump($obj);
}

// Register function to handle new/changed objects pushes
$secucard->registerCallbackObject(gotObjectPush);


// simulate sample push data
// you should set correct target and object and id fields
$raw_event_data = '{
    "object":"event.pushes",
    "id":"EVT_123456789",
    "target":"payment.secupaydebits",
    "type":"changed",
    "data":[
        {
            "object":"payment.secupaydebits",
            "id":"xxxxxxxxx"
        }
    ]
}';

// if the data would be posted to the current script (depends on your url_push configuration for the contract), you can use following code to get posted data
//$raw_event_data = file_get_contents("php://input");

$push_data = json_decode($raw_event_data);

try {
	$secucard->processPush(null, null, $push_data);
} catch (Exception $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
}
