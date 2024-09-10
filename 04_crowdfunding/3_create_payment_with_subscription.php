<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay payment transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\SecupayCreditcard;
use SecucardConnect\Product\Payment\Model\Customer;
use SecucardConnect\Product\Payment\Model\RedirectUrl;
use SecucardConnect\Product\Payment\Model\Subscription;

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

/*
 * 1) Start first payment (f.e. set-up fee)
 */
$payment = new SecupayCreditcard();
$payment->demo = true;
$payment->amount = 1000; // Amount in cents (or in the smallest unit of the given currency)
$payment->currency = 'EUR'; // Can not changed for a created subscription!
$payment->purpose = 'Set-up fee for www.example.com';
$payment->order_id = '201700124'; // The shop order id
$payment->customer = new Customer();
$payment->customer->id = $customer->id;
// The customer will be redirected to "url_success" after you (the shop) has show him the iframe
// and he has filled out the form in this iframe.
// The url of this iframe will be returned in the response of this save request in the variable called "iframe_url".
$payment->redirect_url = new RedirectUrl();
$payment->redirect_url->url_success = 'http://shop.example.com/success.php';
// The customer will be redirected to "url_failure" if we don't accept him for credit card payments.
// You should offer him to pay with other payment methods on this page.
$payment->redirect_url->url_failure = 'http://shop.example.com/failure.php';

// Activate the option to reuse the payment transaction
$payment->subscription = new Subscription();
$payment->subscription->purpose = 'Payment for www.example.com';

try {
	$payment = $service->save($payment);
} catch (\Exception $e) {
	echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($payment->id) {
	echo 'Created secupay creditcard transaction with id: ' . $payment->id . "\n";
	echo 'Payment data: ' . print_r($payment, true) . "\n";
} else {
	echo 'Creating a new payment transaction failed' . "\n";
	exit;
}

/*
 * 2) Save the subscription id for reusing the payment transaction
 */
$subscription_id = (int)$payment->subscription->id;
echo '$subscription_id: ' . $subscription_id . "\n";

/*
 * 3) Redirect the customer to the checkout page
 */
$redirect_url = null;
if (isset($payment->redirect_url->iframe_url)) {
	$redirect_url = $payment->redirect_url->iframe_url;
	// TODO Add your redirect here.
	echo '$redirect_url: ' . $redirect_url . "\n";
}
