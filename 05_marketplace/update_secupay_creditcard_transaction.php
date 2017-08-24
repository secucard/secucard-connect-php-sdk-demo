<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\Basket;

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

$basket = [];

// Add the product/service (92 EUR)
$item = new Basket();
$item->name = 'Booking the Beatles on 29 August';
$item->total = 9200;
$item->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
$item->contract_id = 'PCR_2C0S37QHH2MASN9V875XU3YFNM8UA6';
$basket[] = $item;

// Add the stakeholder position to the payment transaction
try {
    $was_successful = $service->updateBasket('zhzrmbjotubc2209666', $basket);

    if ($was_successful) {
        echo 'Update basket was successful.' . "\n";
    } else {
        echo 'Update basket failed' . "\n";
        exit;
    }
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
    exit;
}

// Release the payout lock
try {
    $was_successful = $service->reverseAccrual('zhzrmbjotubc2209666');

    if ($was_successful) {
        echo 'Release the payout lock was successful.' . "\n";
    } else {
        echo 'Release the payout lock failed' . "\n";
        exit;
    }
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
    exit;
}