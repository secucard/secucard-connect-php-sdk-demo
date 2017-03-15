<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay payment transactions, so you should store created ids.
 */

use SecucardConnect\Product\Common\Model\ApiData;
use SecucardConnect\Product\Payment\Model\SecupayCreditcard;
use SecucardConnect\Product\Payment\Model\Basket;
use SecucardConnect\Product\Payment\Model\Customer;
use SecucardConnect\Product\Payment\Model\Subscription;

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

/*
 * 4) Reuse the created payment transaction (Need a successful first payment transaction)
 */
$subscription_id = 359;
$payment = new SecupayCreditcard();
$payment->api_data = new ApiData();
$payment->api_data->demo = true;
$payment->currency = 'EUR'; // The ISO-4217 code of the currency
$payment->purpose = 'Your support for project XY';
$payment->order_id = '201700125'; // The shop order id
$payment->customer = new Customer();
$payment->customer->id = 'PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9';

// Set the accrual flag, to block the payout to the creator till the crowdfunding phase
$payment->accrual = true;

// Define the creator of the crowdfunding campaign by using the ID:
$payment->contract = 'PCR_2JEW0SVH82M69WYBX75XUZ5A44P5AH';
// or by using the (loaded) contract object:
//$payment->contract = new \SecucardConnect\Product\Payment\Model\Contract();
//$payment->contract->id = 'PCR_2JEW0SVH82M69WYBX75XUZ5A44P5AH';

// Create basket
$payment->basket = [];

// Add the first item
$item_1 = new Basket();
$item_1->item_type = Basket::ITEM_TYPE_ARTICLE;
$item_1->name = 'Super fancy product';
$item_1->price = 1500;
$item_1->quantity = 2;
$item_1->tax = 19;
$item_1->total = 3000;
$payment->basket[] = $item_1;

// Add the shipping costs
$shipping = new Basket();
$shipping->item_type = Basket::ITEM_TYPE_SHIPPING;
$shipping->name = 'Deutsche Post Warensendung';
$shipping->tax = 19;
$shipping->total = 145;
$payment->basket[] = $shipping;

// For payout: add platform fee
$fee = new Basket();
$fee->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
$fee->contract_id = 'PCR_3FA40GKZE2M5MFB8X75XUC4R9GUNA5';
$fee->name = 'platform fee';
$fee->total = 450;
$payment->basket[] = $fee;

// For payout: add "pay what you want"
$pwyw = new Basket();
$pwyw->item_type = Basket::ITEM_TYPE_DONATION;
$pwyw->name = 'PayWhatYouWant';
$pwyw->total = 200;
$payment->basket[] = $pwyw;

// Calculate total amount
$payment->amount = 0;
foreach($payment->basket as $item) {
	$payment->amount += (int)$item->total;
}

// Activate the option to reuse the payment transaction
$payment->subscription = new Subscription();
$payment->subscription->id = $subscription_id;

try {
    $payment = $service->save($payment);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($payment->id) {
    echo 'Created recurring secupay creditcard transaction with id: ' . $payment->id . "\n";
    echo 'Payment data: ' . print_r($payment, true) . "\n";
} else {
	echo 'Creating a new payment transaction failed' . "\n";
    exit;
}