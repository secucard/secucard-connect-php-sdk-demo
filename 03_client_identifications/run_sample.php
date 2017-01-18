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

// Create a new identification request
require 'create_ident_request.php';

// Get an existing identification request
require 'get_ident_request.php';

// Get a list of created identification requests
require 'get_ident_request_list.php';