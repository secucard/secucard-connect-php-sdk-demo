<?php

require_once __DIR__ . '/lib/init.php';

// new container creation:
$container_data = ['type' => 'bank_account',
    'private' => [
        'owner'=> 'John Doe',
        'iban'=> 'FILL_CORRECT_IBAN',
]];


$container = $secucard->factory('Payment\Containers');
$logger->debug('created object');

$container->initValues($container_data);
$logger->debug('object data initialized');

$success = false;
try {
    $success = $container->save();
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
    echo 'Created Container with id: ' . $container->id . "\n";
    echo 'Container data: ' . $container->as_json() . "\n";
} else {
    echo 'Container creation failed';
}
