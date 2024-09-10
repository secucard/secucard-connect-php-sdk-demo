<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\CloneParams;
use SecucardConnect\Product\Payment\Model\Data;

/**
 * @var \SecucardConnect\Product\Payment\ContractsService $service
 */
$service = $secucard->payment->contracts;

$project_name = 'Test project ' . mt_rand();   // or contract_name (must be unique)

// The payment data needs to be valid
$payment_data = new Data('DE62100208900001317270', 'Donald Duck');

$allow_transactions = true;

// Use url_push, when you want to use other url_push than from parent contract
$url_push = null;

// To overwrite the contact data use the following object:
/*
 *  TODO r.simlinger: replace json with the SDK class
"user": {
    "title": "Herr",
    "company": "DIE Firma",
    "firstname": "Test FN",
    "lastname": "Test LN",
    "street": "Test Street",
    "housenumber": "5t",
    "zip": "12345",
    "city": "TestCity",
    "telephone": "+4912342134123",
    "dob_value": "01.02.1903",
    "email": "test@example.com"
}
 */

$params = new CloneParams($project_name, $payment_data, $allow_transactions, $url_push);

try {
    $contract = $service->cloneMyContract($params);
    echo 'New cloned contract data: ' . "\n";
    print_r($contract);
} catch (Exception $e) {
    echo 'Cloning contract failed, error message: ' . $e->getMessage() . "\n";
}
