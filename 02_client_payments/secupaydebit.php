<?php

require_once __DIR__ . '/lib/init.php';

// new debit payment creation:
$debit_data = [
    'container' => [
        'object' => 'payment.containers',
        'id' => 'PCT_XXXX',
    ],
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

$object = $secucard->factory('Payment\Secupaydebits');
$logger->debug('created object');

$object->initValues($debit_data);
$logger->debug('object data initialized');

$success = false;
try {
    $success = $object->save();echo 'finished';
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
    echo 'Created Secupaydebits with id: ' . $object->id . "\n";
    echo 'Debit data: ' . $object->as_json() . "\n";
} else {
    echo 'Debit creation failed'. "\n";
}