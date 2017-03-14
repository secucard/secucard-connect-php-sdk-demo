<?php

// Load the vendor autoload file
require_once __DIR__ . '/../shared/init.php';

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

// Define the required config params
$config = [
    'base_url' => 'https://connect-testing.secupay-ag.de', // demo server
//    'base_url' => 'https://connect.secucard.com', // live
    'debug'    => false // TODO Set to TRUE to display the http client logs
];

// Create a dummy logger
$logger = new Logger(fopen("php://stdout", "a"), false); // TODO Set to TRUE to display the sdk logging stuff

// Create a dummy value storage
$store = new DummyStorage();

// Create credentials storage
$clientId = '09ae83af7c37121b2de929b211bad944'; // TODO Add your secucard client id (current length: 32 chars)
$clientSecret = '9c5f250b69f6436cb38fd780349bc00810d8d5051d3dcf821e428f65a32724bd'; // TODO Add your secucard client secret (current length: 64 chars)
$cred = new ClientCredentials($clientId, $clientSecret);

// Initialize the SecucardConnect client
$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);

// 1) Create a new project
require '1_create_new_project.php';

// For each new crowdfunder:

// 2) Create a new customer
require '2_create_customer.php';

// 3) Create a new (first) payment transaction with credit card for reuse this payment later
require '3_create_payment_with_subscription.php';

// 4) Get the status of a created payment transaction
require '4_get_payment_status.php';

// 5) Create a second payment transaction with credit card for the last payment transaction
require '5_reuse_payment.php';

// 6) Reverse accrual
// TODO r.simlinger: require '6_reverse_accrual.php';


