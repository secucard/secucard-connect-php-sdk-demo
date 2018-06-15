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
$config->setDebug(false);

// Use the sandbox environment
$config->setBaseUrl('https://connect-dev6.secupay-ag.de');

// Create a dummy logger
$logger = new Logger(fopen("php://stdout", "a"), false);

// Create a dummy value storage
$store = new DummyStorage();

// Create credentials storage
$credPayments = new ClientCredentials(
    'f469ce017de896bbc103137f41fedfdd',
    '6b0dc65087d4e259aac7a04781381eedd3c040b51f472522809dd6a4440985a4'
);

// Initialize the SecucardConnect client
$secucard = new SecucardConnect($config, $logger, $store, $store, $credPayments);

// Create a new customer
require 'create_customer.php';
//$customer = "PCU_NAAWQ4KRC2MXPWRQ2F9VH98FWCAGA2";

// Create a new payment transaction for credit card
require 'create_secupay_prepay_transaction.php';
// redirect the customer to the payment checkout page (iframe)


// get details (parent TA)
/**
 * @var \SecucardConnect\Product\Payment\SecupayPrepaysService $service
 */
$service = $secucard->payment->secupayprepays;
try {
    $payment = $service->get($payment->id);
    echo 'Payment data: ' . print_r($payment, true) . "\n";
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

exit;

// get details (sub-TA)
try {
//    $payment = $service->reverseAccrual("ashfcxwhmuam2647835_11577418");
    $result = $service->cancel($payment->sub_transactions[0]->id, null, 190, true);
    echo 'Cancel Payment data: ' . print_r($result, true) . "\n";
    $payment = $service->get($payment->sub_transactions[0]->id);
    $service->capture();
    echo 'Payment data: ' . print_r($payment, true) . "\n";

} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}
