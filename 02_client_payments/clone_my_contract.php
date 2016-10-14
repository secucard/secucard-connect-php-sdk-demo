<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\CloneParams;
use SecucardConnect\Product\Payment\Model\Data;

/**
 * @var \SecucardConnect\Product\Payment\ContractsService $service
 */
$service = $secucard->payment->contracts;

$project_name = 'Test project 1';   // or contract_name

// The payment data needs to be valid
$payment_data = new Data('DE62100208900001317270', 'Donald Duck');

// Set allow transactions to false, if you don't want to be able to create transactions with current contract (but then the contract is useless)
$allow_transactions = true;

// Use url_push, when you want to use other url_push than from parent contract
$url_push = null;

$params = new CloneParams($project_name, $payment_data, $allow_transactions, $url_push);

try {
    $contract = $service->cloneMyContract($params);
    echo 'New cloned contract data: ' . "\n";
    print_r($contract);
} catch (Exception $e) {
    echo 'Cloning contract failed, error message: ' . $e->getMessage() . "\n";
}


/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Cloning contract failed, error message: Client error: `POST https://connect-testing.secupay-ag.de/api/v2/Payment/Contracts/me/clone` resulted in a `403 ProductNotAllowedException` response:
{"status":"error","error":"ProductNotAllowedException","error_details":"access denied","error_user":"Es ist ein unbekann (truncated...)

 */