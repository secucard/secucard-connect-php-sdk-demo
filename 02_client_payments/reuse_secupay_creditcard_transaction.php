<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay creditcard transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\SecupayCreditcard;

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

$creditcard = new SecupayCreditcard();
$creditcard->amount = 100; // Amount in cents (or in the smallest unit of the given currency)
$creditcard->currency = 'EUR'; // The ISO-4217 code of the currency
$creditcard->purpose = 'Your purpose from TestShopName';
$creditcard->order_id = '201600123'; // The shop order id
$creditcard->customer = new \SecucardConnect\Product\Payment\Model\Customer();
$creditcard->customer->id = 'PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9';
// The customer will be redirected to "url_success" after you (the shop) has show him the iframe
// and he has filled out the form in this iframe.
// The url of this iframe will be returned in the response of this save request in the variable called "iframe_url".
$creditcard->url_success = 'http://shop.example.com/success.php';
// The customer will be redirected to "url_failure" if we don't accept him for credit card payments.
// You should offer him to pay with other payment methods on this page.
$creditcard->url_failure = 'http://shop.example.com/failure.php';
// optional: $creditcard->basket = ...

// Activate the option to reuse the payment transaction
$creditcard->subscription = new \SecucardConnect\Product\Payment\Model\Subscription();
$creditcard->subscription->purpose = $creditcard->purpose;

try {
    $creditcard = $service->save($creditcard);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($creditcard->id) {
    echo 'Created secupay creditcard transaction with id: ' . $creditcard->id . "\n";
    echo 'Creditcard data: ' . print_r($creditcard, true) . "\n";
} else {
    echo 'Creditcard creation failed' . "\n";
    exit;
}


// reuse the created payment transaction

$subscription_id = (int)$creditcard->subscription->id;

$creditcard = new SecupayCreditcard();
$creditcard->amount = 100; // Amount in cents (or in the smallest unit of the given currency)
$creditcard->currency = 'EUR'; // Can not changed for a created subscription!
$creditcard->purpose = 'Your purpose from TestShopName';
$creditcard->order_id = '201600124'; // The shop order id
$creditcard->customer = new \SecucardConnect\Product\Payment\Model\Customer();
$creditcard->customer->id = 'PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9';
// optional: ->basket

// Activate the option to reuse the payment transaction
$creditcard->subscription = new \SecucardConnect\Product\Payment\Model\Subscription();
$creditcard->subscription->id = $subscription_id;
$creditcard->subscription->purpose = $creditcard->purpose;

try {
	$creditcard = $service->save($creditcard);
} catch (\Exception $e) {
	echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($creditcard->id) {
	echo 'Created secupay creditcard transaction with id: ' . $creditcard->id . "\n";
	echo 'Creditcard data: ' . print_r($creditcard, true) . "\n";
} else {
	echo 'Creditcard creation failed' . "\n";
	exit;
}

/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created secupay creditcard transaction with id: msbytywxhfip1647592
Creditcard data: SecucardConnect\Product\Payment\Model\SecupayCreditcard Object
(
    [contract] =>
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] => DateTime Object
                (
                    [date] => 2017-01-13 08:46:56.000000
                    [timezone_type] => 1
                    [timezone] => +01:00
                )

            [updated] =>
            [contract] => SecucardConnect\Product\Payment\Model\Contract Object
                (
                    [created] =>
                    [updated] =>
                    [parent] =>
                    [allow_cloning] =>
                    [id] => PCR_3MUDP20MP2M3M2HWX75XUQYPFK2JA9
                    [object] => payment.contracts
                    [api_data] =>
                )

            [contact] => SecucardConnect\Product\Common\Model\Contact Object
                (
                    [salutation] => Mr.
                    [title] => Dr.
                    [forename] => John
                    [surname] => Doe
                    [name] => John Doe
                    [companyname] => Testfirma
                    [dob] => DateTime Object
                        (
                            [date] => 1971-02-03 00:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [birthplace] => MyBirthplace
                    [nationality] => DE
                    [gender] =>
                    [phone] => 0049-123456789
                    [mobile] =>
                    [email] => example@example.com
                    [picture] =>
                    [pictureObject] =>
                    [url_website] =>
                    [address] => SecucardConnect\Product\Common\Model\Address Object
                        (
                            [street] => Example Street
                            [street_number] => 6a
                            [city] => ExampleCity
                            [postal_code] => 01234
                            [country] => Deutschland
                            [id] =>
                            [object] =>
                            [api_data] =>
                        )

                )

            [merchant] =>
            [id] => PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9
            [object] => payment.customers
            [api_data] =>
        )

    [recipient] =>
    [amount] => 100
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 201600123
    [trans_id] => 8476016
    [status] => internal_server_status
    [transaction_status] => 1
    [basket] =>
    [experience] =>
    [accrual] =>
    [subscription] => SecucardConnect\Product\Payment\Model\Subscription Object
        (
            [purpose] => Your purpose from TestShopName
            [id] => 347
            [object] =>
            [api_data] =>
        )

    [redirect_url] =>
    [opt_data] =>
    [id] => msbytywxhfip1647592
    [object] => payment.secupaycreditcards
    [api_data] =>
)

 */