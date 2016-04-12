<?php

/**
* THIS IS SAMPLE command line script to create payment.secupaydebits object
* To use it, update @variables with correct values and run command "php secupaydebit.php"
*/

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Payment\Model\SecupayDebit;
use SecucardConnect\Product\Payment\Model\Customer;
use SecucardConnect\Product\Payment\Model\Container;
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

$service = $secucard->payment->secupaydebits;

// it is not allowed to list all already created secupaydebits, so you should store created ids

// new debit payment creation:
$customer = new Customer();
$customer->object = 'payment.customers';
$customer->id = '@your-created-customer-id';

$container = new Container();
$container->object = 'payment.containers';
$container->id = '@your-created-container-id';

$debit = new SecupayDebit();
$debit->amount = 100; // amount in cents
$debit->currency = 'EUR';
$debit->purpose = 'Test purpose';
$debit->order_id = 'your-order-id';
$debit->customer = $customer;
$debit->container = $container;

// if you want to create prepay payment for a cloned contract (contract that you created by cloning main contract)
/*
$contract = new Contract();
$contract->id = 'PCR_XXXX';
$contract->object = 'payment.contracts';
$debit->contract = $contract;
*/

try {
    $debit = $service->save($debit);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($debit->id) {
    echo 'Created Secupaydebits with id: ' . $debit->id . "\n";
    echo 'Debit data: ' . print_r($debit, true) . "\n";
} else {
    echo 'Debit creation failed'. "\n";
}
