<?php

// Load the vendor autoload file
require_once __DIR__ . '/../shared/init.php';

use SecucardConnect\ApiClientConfiguration;
use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

// Define the required config params
$config = new ApiClientConfiguration();

// Use the sandbox environment
$config->setBaseUrl('https://connect-testing.secupay-ag.de');

// Create a dummy logger
$logger = new Logger(fopen("php://stdout", "a"), false);

// Create a dummy value storage
$store = new DummyStorage();

// Create credentials storage
$credPayments = new ClientCredentials(
    '09ae83af7c37121b2de929b211bad944',
    '9c5f250b69f6436cb38fd780349bc00810d8d5051d3dcf821e428f65a32724bd'
);

// Initialize the SecucardConnect client
$secucard = new SecucardConnect($config, $logger, $store, $store, $credPayments);

// Create a new customer
require 'create_customer.php';

// Create a new payment transaction for credit card
require 'create_secupay_prepay_transaction.php';

// redirect the customer to the payment checkout page (iframe)