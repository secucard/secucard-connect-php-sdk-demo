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
    'debug' => false // TODO Set to TRUE to display the http client logs
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
// -> created ID: PCR_2JEW0SVH82M69WYBX75XUZ5A44P5AH

// For each new crowdfunder:

// 2) Create a new customer
require '2_create_customer.php';
// -> created ID: PCU_WRNBA4NCU2M69WYF875XUKYA44P5AH

// 3) Create a new (first) payment transaction with credit card for reuse this payment later
require '3_create_payment_with_subscription.php';
// -> created ID: ocotcwttcxbu1648101
// -> created subscription ID: 370

// 4) Get the status of a created payment transaction
require '4_get_payment_status.php';

// 5) Create a second payment transaction with credit card for the last payment transaction
require '5_reuse_payment.php';

// 6) Get details about the payout of a project
require "6_get_project_details.php";

// 8) Upload Ident Data
require "8_upload_ident_data.php";

// 9) Update bank account for a contract
require '9_update_bank_account.php';

