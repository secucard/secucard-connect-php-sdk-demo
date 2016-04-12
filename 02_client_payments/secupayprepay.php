<?php

/**
* THIS IS SAMPLE command line script to create payment.secupayprepays object
* To use it, update @variables with correct values and run command "php secupayprepay.php"
*/

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Payment\Model\SecupayPrepay;
use SecucardConnect\Product\Payment\Model\Customer;
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

$service = $secucard->payment->secupayprepays;

// it is not allowed to list all already created secupayprepays, so you should store created ids

// new prepay payment creation:
$customer = new Customer();
$customer->object = 'payment.customers';
$customer->id = '@your-created-customer-id';

$prepay = new SecupayPrepay();
$prepay->amount = 100; // amount in cents
$prepay->currency = 'EUR';
$prepay->purpose = 'Test purpose';
$prepay->order_id = 'your-order-id';
$prepay->customer = $customer;

// if you want to create prepay payment for a cloned contract (contract that you created by cloning main contract)
/*
$contract = new Contract();
$contract->id = 'PCR_XXXX';
$contract->object = 'payment.contracts';
$prepay->contract = $contract;
*/

try {
    $prepay = $service->save($prepay);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($prepay->id) {
    echo 'Created Secupayprepays with id: ' . $prepay->id . "\n";
    echo 'Prepay data: ' . print_r($prepay, true) . "\n";
} else {
    echo 'Prepay creation failed'. "\n";
}