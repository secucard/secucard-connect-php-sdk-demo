<?php

require_once __DIR__ . '/lib/init.php';

/**
 * Function that will handle the push
 */
function gotObjectPush(secucard\client\base\MainModel $obj) {
    var_dump($obj);
}

// Register function to handle new/changed objects pushes
$secucard->registerCallbackObject(gotObjectPush);



// simulate sample push
$raw_post_data = '{
    "object":"event.pushes",
    "id":"EVT_123456789",
    "target":"payment.secupaydebits",
    "type":"changed",
    "data":{
        "object":"payment.secupaydebits",
        "id":"xxxxxxxxx"
    }
}';

//$raw_post_data = file_get_contents("php://input");

$push_data = json_decode($raw_post_data);


try {
	$secucard->processPush(null, null, $push_data);
} catch (Exception $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
}
