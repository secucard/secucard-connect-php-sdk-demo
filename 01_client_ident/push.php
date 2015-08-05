<?php

function gotObjectPush(secucard\client\base\MainModel $obj) {
    var_dump($obj);
}

// Register function to handle new/changed objects
$secucard->registerCallbackObject('gotObjectPush');

// process request
$secucard->processPush();