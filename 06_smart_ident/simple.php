<?php

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Smart\Model\Ident;
use SecucardConnect\Product\Loyalty\Model\CardGroup;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

require_once __DIR__ . '/../shared/init.php';

$config = array(
    'base_url' => 'https://connect.secucard.com', // live
    'debug' => true
);

// Create a dummy logger
$logger = new Logger(fopen("php://stdout", "a"), false); // TODO Set to TRUE to display the sdk logging stuff

// Create a dummy value storage
$store = new DummyStorage();

// Create credentials storage
$clientId = 'your-client-id'; // TODO Add your secucard client id (current length: 32 chars)
$clientSecret = 'your-client-secret'; // TODO Add your secucard client secret (current length: 64 chars)

$cred = new ClientCredentials($clientId, $clientSecret);

// Initialize the SecucardConnect client
$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);

// Your loyalty card number
$cardnumber = "9276"; // TODO Add your card number here

// Get loyalty card information
$resultGetCardInfo = $secucard->smart->idents->getCardInfo($cardnumber, Ident::TYPE_CARD);

var_dump($resultGetCardInfo);

// Get the cardgroupid from loyalty card
$crgID = $resultGetCardInfo[0]->merchantcard->cardgroup->id;

// Check if for the given card a passcode is set
$resultCheckPassCode = $secucard->loyalty->cardgroups->checkPasscodeEnabled($crgID, CardGroup::TRANSACTION_TYPE_DISCHARGE, $cardnumber);

var_dump($resultCheckPassCode);

// Validate if the given passcode is correct
$resultValidatePIN = $secucard->loyalty->merchantcards->validatePasscode($cardnumber, 7124);

var_dump($resultValidatePIN);

// Validate if the given CSC number is correct
$resultValidateCSC = $secucard->loyalty->merchantcards->validateCSC($cardnumber, 6825);

var_dump($resultValidateCSC);