<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\SecupayCreditcard;
use SecucardConnect\Product\Payment\Model\RedirectUrl;
use SecucardConnect\Product\Payment\Model\Basket;

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

$payment = new SecupayCreditcard();
$payment->amount = 10000;    // corresponds 100.00 EUR
$payment->currency = 'EUR';
$payment->purpose = 'Your purpose from TestShopName';
$payment->order_id = '2017000123';
$payment->customer = $customer;
$payment->redirect_url = new RedirectUrl();
$payment->redirect_url->url_success = 'http://shop.example.com/success.php';
$payment->redirect_url->url_failure = 'http://shop.example.com/failure.php';

// Block the payout till the date of delivery (of the product or service)
$payment->accrual = true;

// Add the marketplace fee (8 EUR)
$item = new Basket();
$item->name = 'Account management fee';
$item->quantity = 1;
$item->price = 800;
$item->total = 800;
$item->item_type = Basket::ITEM_TYPE_ARTICLE;
$payment->basket[] = $item;

// Add the product/service (92 EUR)
$item = new Basket();
$item->name = 'Booking the Beatles on 29 August';
$item->quantity = 1;
$item->price = 9200;
$item->total = 9200;
$item->tax = 1900;
$item->item_type = Basket::ITEM_TYPE_ARTICLE;
$payment->basket[] = $item;

// Create the payment transaction
try {
    $payment = $service->save($payment);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}


if ($payment->id) {
    echo 'Created secupay payment transaction with id: ' . $payment->id . "\n";
    echo 'Payment data: ' . print_r($payment, true) . "\n";
} else {
    echo 'Creation of the payment transaction failed' . "\n";
    exit;
}

/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created secupay payment transaction with id: zhzrmbjotubc2209666
Payment data: SecucardConnect\Product\Payment\Model\SecupayCreditcard Object
(
    [contract] =>
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] => DateTime Object
                (
                    [date] => 2017-08-24 13:01:49.000000
                    [timezone_type] => 1
                    [timezone] => +02:00
                )

            [updated] =>
            [contract] => SecucardConnect\Product\Payment\Model\Contract Object
                (
                    [created] =>
                    [updated] =>
                    [parent] =>
                    [allow_cloning] =>
                    [id] => PCR_2C0S37QHH2MASN9V875XU3YFNM8UA6
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
                            [country] => Deutschland
                            [id] =>
                            [object] =>
                        )

                )

            [merchant] =>
            [merchant_customer_id] =>
            [id] => PCU_3WFU33T2W2MCUM8EX75XU4TSX4JBAE
            [object] => payment.customers
        )

    [recipient] =>
    [amount] => 10000
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 2017000123
    [trans_id] => 9983711
    [status] => internal_server_status
    [transaction_status] => 1
    [basket] => Array
        (
            [0] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 1
                    [name] => Account management fee
                    [ean] =>
                    [tax] =>
                    [total] => 800
                    [price] => 800
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => article
                    [apikey] =>
                    [id] =>
                    [object] =>
                )

            [1] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 1
                    [name] => Booking the Beatles on 29 August
                    [ean] =>
                    [tax] => 1900
                    [total] => 9200
                    [price] => 9200
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => article
                    [apikey] =>
                    [id] =>
                    [object] =>
                )

        )

    [experience] =>
    [accrual] => 1
    [subscription] =>
    [redirect_url] => SecucardConnect\Product\Payment\Model\RedirectUrl Object
        (
            [url_success] => http://shop.example.com/success.php
            [url_failure] => http://shop.example.com/failure.php
            [iframe_url] => https://api-testing.secupay-ag.de/payment/zhzrmbjotubc2209666
        )

    [url_success] =>
    [url_failure] =>
    [iframe_url] => https://api-testing.secupay-ag.de/payment/zhzrmbjotubc2209666
    [opt_data] =>
    [payment_action] => sale
    [used_payment_instrument] =>
    [id] => zhzrmbjotubc2209666
    [object] => payment.secupaycreditcards
)

 */