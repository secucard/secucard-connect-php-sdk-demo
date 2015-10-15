<?php

include "lib/init.php";

function gotObjectPush(secucard\client\base\MainModel $obj) {
    var_dump($obj);
}
// simulate sample push
$raw_post_data = '{
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
//$raw_post_data = file_get_contents("php://input");

$push_data = json_decode($raw_post_data);

// Register function to handle new/changed objects
$secucard->registerCallbackObject('gotObjectPush');

// process request
$secucard->processPush(null, null, $push_data);
