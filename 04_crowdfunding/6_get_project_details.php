<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\TransactionsService $service
 */
$service = $secucard->payment->transactions;

try {
    $payment = $service->getCrowdFundingData("MRC_WVHJQFQ4JNVYNG5B55TYK748ZCHQP8"); // replace, the demo ID will throw an error

    if ($payment) {
        echo 'Payment data: ' . print_r($payment, true) . "\n";
    } else {
        echo 'Getting Crowd-Funding-Data failed' . "\n";
        exit;
    }
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
    // Error message: multiple currency not supported
}

try {
    $query = new \SecucardConnect\Client\QueryParams();
    $query->count = 1; // default: 20
    $query->query = 'merchant.id:MRC_WVHJQFQ4JNVYNG5B55TYK748ZCHQP8';
    $payment = $service->getList($query);

    if ($payment) {
        echo 'Payment data: ' . print_r($payment, true) . "\n";
    } else {
        echo 'Getting list of payment transactions failed' . "\n";
        exit;
    }
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}



