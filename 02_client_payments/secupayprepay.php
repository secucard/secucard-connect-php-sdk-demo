<?php

require_once __DIR__ . '/lib/init.php';

// new prepay payment creation:
$prepay_data = [
    'customer' => [
        'object' => 'payment.customers',
        'id' => 'PCU_XXXX',
    ],
    'contract' => [
        'object' => 'payment.contracts',
        'id' => 'PCR_XXXX',
    ],
    'amount' => '100',
    'currency' => 'EUR',
    'purpose' => 'purpose_text',
    'order_id' => 'ZZZZZZ'
];

$object = $secucard->factory('Payment\Secupayprepays');
$logger->debug('created object');

$object->initValues($prepay_data);
$logger->debug('object data initialized');

$success = false;
try {
    $success = $object->save();
} catch (\GuzzleHttp\Exception\TransferException $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        if ($e->getResponse()->getBody()) {
            echo 'Body: ' . json_encode($e->getResponse()->getBody()->__toString()) . "\n";
        }
    }
} catch (Exception $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
}

if ($success) {
    echo 'Created Secupayprepays with id: ' . $object->id . "\n";
    echo 'Prepay data: ' . $object->as_json() . "\n";
} else {
    echo 'Prepay creation failed'. "\n";
}