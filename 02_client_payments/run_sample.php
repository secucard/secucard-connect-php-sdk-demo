<?php

// Load the vendor autoload file
require_once __DIR__ . '/../shared/init.php';

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

// Define the required config params
$config = \SecucardConnect\ApiClientConfiguration::createFromArray([
    'base_url' => 'https://connect-testing.secupay-ag.de', // demo server
//    'base_url' => 'https://connect.secucard.com', // live
    'debug'    => false // TODO Set to TRUE to display the http client logs
]);

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

// Get enabled payment methods
require 'get_payment_methods.php';

// Create a new customer
require 'create_customer.php';

// Create a new payment container (only needed for secupay debit, not for prepay)
require 'create_container.php';

// Create a new payment transaction with secupay debit
require 'create_secupay_debit_transaction.php';

// Create a new payment transaction with secupay invoice
require 'create_secupay_invoice_transaction.php';

// Create a new payment transaction with secupay prepay
require 'create_secupay_prepay_transaction.php';

// Create a new payment transaction with secupay credit card
require 'create_secupay_creditcard_transaction.php';

// Get the status of a created payment transaction (with credit card)
require 'get_secupay_creditcard_transaction.php';

// Cancel a created payment transaction (with secupay prepay)
require 'cancel_secupay_prepay_transaction.php';

// List all created customers
require 'get_customer_list.php';

// List all created payment containers
require 'get_container_list.php';

// Clone my (main) contract (Must be activated separately in the contract)
// require 'clone_my_contract.php';

// Test the push service
require 'test_push_service.php';
