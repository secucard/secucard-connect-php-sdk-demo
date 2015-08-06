<?php

require_once __DIR__ . '/lib/init.php';

/**
 * Function that will handle the push
 */
function gotObjectPush($related_obj) {
    var_dump($related_obj);
}

// Register function to handle new/changed objects pushes
$secucard->registerCallbackObject(gotObjectPush);

// simulate sample push
$sample_push_data = '{"object":"event.pushes","id":"EVT_123456789", "target":"general.skeletons", "type":"changed", "data":{"object":"general.skeletons", "id":"SKL_WP65ADX3XUPMZFHVKKMZWCKPTP4SRM"}}';

$sample_push_obj = json_decode($sample_push_data);

try {
	$secucard->processPush(null, null, $sample_push_obj);
} catch (Exception $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
}
