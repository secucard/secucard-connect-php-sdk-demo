<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay payment transactions, so you should store created ids.
 */

use SecucardConnect\Product\Common\Model\ApiData;
use SecucardConnect\Product\Payment\Model\SecupayCreditcard;
use SecucardConnect\Product\Payment\Model\Basket;
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
$payment->api_data = new ApiData();
$payment->api_data->demo = true;
$payment->amount = 1000; // Amount in cents (or in the smallest unit of the given currency)
$payment->currency = 'EUR'; // Can not changed for a created subscription!
$payment->purpose = 'Set-up fee for www.example.com';
$payment->order_id = '201700124'; // The shop order id
$payment->customer = new Customer();
$payment->customer->id = 'PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9';
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
$pwyw->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
$pwyw->contract_id = 'PCR_WCP9R04682M5MFBFX75XUC4R9GUNA6';
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



/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

(1)
Created secupay creditcard transaction with id: pxevyyyjusda1647753
Payment data: SecucardConnect\Product\Payment\Model\SecupayCreditcard Object
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
                            [country] => DE
                            [id] =>
                            [object] =>
                        )

                    [ident_service_ids] => Array
                        (
                        )

                )

            [merchant] =>
            [id] => PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9
            [object] => payment.customers
        )

    [recipient] =>
    [amount] => 1000
    [currency] => EUR
    [purpose] => Set-up fee for www.example.com
    [order_id] => 201700124
    [trans_id] => 8477003
    [status] => proceed
    [transaction_status] =>
    [basket] =>
    [experience] =>
    [accrual] =>
    [subscription] => SecucardConnect\Product\Payment\Model\Subscription Object
        (
            [purpose] => Payment for www.example.com
            [id] => 360
            [object] =>
        )

    [redirect_url] => SecucardConnect\Product\Payment\Model\RedirectUrl Object
        (
            [url_success] => http://shop.example.com/success.php
            [url_failure] => http://shop.example.com/failure.php
            [iframe_url] => https://api-testing.secupay-ag.de/payment/pxevyyyjusda1647753
            [id] =>
            [object] =>
        )

    [opt_data] =>
    [api_data] =>
    [id] => pxevyyyjusda1647753
    [object] => payment.secupaycreditcards
)


(2)
$subscription_id: 360


(3)
$redirect_url: https://api-testing.secupay-ag.de/payment/pxevyyyjusda1647753


(4)
Created recurring secupay creditcard transaction with id: nocejdxwjfkk1647752
Payment data: SecucardConnect\Product\Payment\Model\SecupayCreditcard Object
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
                            [country] => DE
                            [id] =>
                            [object] =>
                        )

                    [ident_service_ids] => Array
                        (
                        )

                )

            [merchant] =>
            [id] => PCU_T82YMNGCT2M3XGNX875XUB07FK2JA9
            [object] => payment.customers
        )

    [recipient] =>
    [amount] => 3795
    [currency] => EUR
    [purpose] => Your support for project XY
    [order_id] => 201700124
    [trans_id] => 8477002
    [status] => accepted
    [transaction_status] => 11
    [basket] => Array
        (
            [0] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 2
                    [name] => Super fancy product
                    [ean] =>
                    [tax] => 19
                    [total] => 3000
                    [price] => 1500
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => article
                    [id] =>
                    [object] =>
                )

            [1] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 0
                    [name] => Deutsche Post Warensendung
                    [ean] =>
                    [tax] => 19
                    [total] => 145
                    [price] => 0
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => shipping
                    [id] =>
                    [object] =>
                )

            [2] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 0
                    [name] => platform fee
                    [ean] =>
                    [tax] => 0
                    [total] => 450
                    [price] => 0
                    [contract_id] => PCR_3FA40GKZE2M5MFB8X75XUC4R9GUNA5
                    [model] =>
                    [article_number] =>
                    [item_type] => stakeholder_payment
                    [id] =>
                    [object] =>
                )

            [3] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 0
                    [name] => PayWhatYouWant
                    [ean] =>
                    [tax] => 0
                    [total] => 200
                    [price] => 0
                    [contract_id] => PCR_WCP9R04682M5MFBFX75XUC4R9GUNA6
                    [model] =>
                    [article_number] =>
                    [item_type] => stakeholder_payment
                    [id] =>
                    [object] =>
                )

        )

    [experience] =>
    [accrual] =>
    [subscription] => SecucardConnect\Product\Payment\Model\Subscription Object
        (
            [purpose] =>
            [id] => 359
            [object] =>
        )

    [redirect_url] =>
    [opt_data] =>
    [api_data] =>
    [id] => nocejdxwjfkk1647752
    [object] => payment.secupaycreditcards
)

 */
