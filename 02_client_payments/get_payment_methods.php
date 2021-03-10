<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\ContractsService $service
 */
$service = $secucard->payment->contracts;


try {
    $response = $service->getPaymentMethods();

    /*
        array(5) {
            [0] => string(5) "debit"
            [1] => string(10) "creditcard"
            [2] => string(7) "invoice"
            [3] => string(6) "sofort"
            [4] => string(6) "prepay"
        }
     */
    var_dump($response);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}
