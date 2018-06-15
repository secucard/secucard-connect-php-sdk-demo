<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\SecupayPrepay;
use SecucardConnect\Product\Payment\Model\RedirectUrl;
use SecucardConnect\Product\Payment\Model\Basket;

const APIKEY_PLATFORM = '3e48bb4c1cf162c644a1f10d7e86bb83a3b1788d';
const APIKEY_MERCHANT = '86ccab3909c993efcad51b918fc2479827940f33';
const APIKEY_SUPPLIER = 'dd0d86a14ea00c14888b48e408851bac8d7a44de';

/**
 * @var \SecucardConnect\Product\Payment\SecupayPrepaysService $service
 */
$service = $secucard->payment->secupayprepays;

$payment = new SecupayPrepay();
$payment->amount = 10000;    // corresponds 100.00 EUR
$payment->currency = 'EUR';
$payment->purpose = 'Your purpose from TestShopName';
$payment->order_id = '2018000123';
$payment->customer = $customer;
$payment->redirect_url = new RedirectUrl();
$payment->redirect_url->url_success = 'http://shop.example.com/success.php';
$payment->redirect_url->url_failure = 'http://shop.example.com/failure.php';
$payment->redirect_url->url_push = 'http://shop.example.com/push.php';

// Optional: Block the payout till the date of delivery (of the product or service)
$payment->accrual = true;

$total = 0;
$payment->basket = [];

/*
 * Define the first sub-transaction
 */
{
    $sub_total_1 = 0;
    $item_1 = new Basket();
    $item_1->reference_id = '52534';
    $item_1->apikey = APIKEY_SUPPLIER;
    $item_1->item_type = Basket::ITEM_TYPE_SUB_TRANSACTION;
    $item_1->name = 'Position 1 aus Bestellung 000x';

    // Add sub basket items
    $item_1->sub_basket = [];
    {
        /*
         *
         * Define the first sub item
         *
         */
        $sub_item_1_1 = new Basket();
        $sub_item_1_1->reference_id = '52534.1';
        $sub_item_1_1->quantity = 2;
        $sub_item_1_1->name = 'Position 1 Artikel A';
        $sub_item_1_1->ean = 'EAN001';
        $sub_item_1_1->tax = 19;
        $sub_item_1_1->price = 900;
        $sub_item_1_1->item_type = Basket::ITEM_TYPE_ARTICLE;

        // Calc sums for the first item
        $sub_item_1_1->total = $sub_item_1_1->quantity * $sub_item_1_1->price;
        $sub_total_1 += $sub_item_1_1->total;

        // Add it to the sub basket
        $item_1->sub_basket[] = $sub_item_1_1;

        /*
         *
         * Define the second sub item
         *
         */
        $sub_item_1_2 = new Basket();
        $sub_item_1_2->reference_id = '52534.2';
        $sub_item_1_2->quantity = 2;
        $sub_item_1_2->name = 'Position 1 Artikel AA';
        $sub_item_1_2->ean = 'EAN011';
        $sub_item_1_2->tax = 19;
        $sub_item_1_2->price = 50;
        $sub_item_1_2->item_type = Basket::ITEM_TYPE_ARTICLE;

        // Calc sums for the first item
        $sub_item_1_2->total = $sub_item_1_2->quantity * $sub_item_1_2->price;
        $sub_total_1 += $sub_item_1_2->total;

        // Add it to the sub basket
        $item_1->sub_basket[] = $sub_item_1_2;

        /*
         *
         * Define the first stakeholder item
         *
         */
        $sub_item_1_3 = new Basket();
        $sub_item_1_3->reference_id = '52534.3';
        $sub_item_1_3->apikey = APIKEY_PLATFORM;
        $sub_item_1_3->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_1_3->name = 'Position 1 plattform provision';
        $sub_item_1_3->total = 200;

        // Add it to the sub basket
        $item_1->sub_basket[] = $sub_item_1_3;

        /*
         *
         * Define the second stakeholder item
         *
         */
        $sub_item_1_4 = new Basket();
        $sub_item_1_4->reference_id = '52534.4';
        $sub_item_1_4->apikey = APIKEY_MERCHANT;
        $sub_item_1_4->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_1_4->name = 'Position 1 merchant provision';
        $sub_item_1_4->total = 100;

        // Add it to the sub basket
        $item_1->sub_basket[] = $sub_item_1_4;
    }

    // Add item to the main basket
    $total += $sub_total_1;
    $item_1->total = $sub_total_1;
    $payment->basket[] = $item_1;
}

/*
 * Define the second sub-transaction
 */
{
    $sub_total_2 = 0;
    $item_2 = new Basket();
    $item_2->reference_id = '52535';
    $item_2->apikey = APIKEY_SUPPLIER;
    $item_2->item_type = Basket::ITEM_TYPE_SUB_TRANSACTION;
    $item_2->name = 'Position 2 aus Bestellung 000x';

    // Add sub basket items
    $item_2->sub_basket = [];
    {
        /*
         * 
         * Define the first sub item
         * 
         */
        $sub_item_2_1 = new Basket();
        $sub_item_2_1->reference_id = '52535.1';
        $sub_item_2_1->quantity = 1;
        $sub_item_2_1->name = 'Position 2 Artikel A';
        $sub_item_2_1->ean = 'EAN002';
        $sub_item_2_1->tax = 19;
        $sub_item_2_1->price = 1000;
        $sub_item_2_1->item_type = Basket::ITEM_TYPE_ARTICLE;

        // Calc sums for the first item
        $sub_item_2_1->total = $sub_item_2_1->quantity * $sub_item_2_1->price;
        $sub_total_2 += $sub_item_2_1->total;

        // Add it to the sub basket
        $item_2->sub_basket[] = $sub_item_2_1;

        /*
         *
         * Define the first stakeholder item
         *
         */
        $sub_item_2_2 = new Basket();
        $sub_item_2_2->reference_id = '52535.2';
        $sub_item_2_2->apikey = APIKEY_PLATFORM;
        $sub_item_2_2->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_2_2->name = 'Position 2 plattform provision';
        $sub_item_2_2->total = 200;

        // Add it to the sub basket
        $item_2->sub_basket[] = $sub_item_2_2;

        /*
         *
         * Define the second stakeholder item
         *
         */
        $sub_item_2_3 = new Basket();
        $sub_item_2_3->reference_id = '52535.3';
        $sub_item_2_3->apikey = APIKEY_MERCHANT;
        $sub_item_2_3->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_2_3->name = 'Position 2 merchant provision';
        $sub_item_2_3->total = 100;

        // Add it to the sub basket
        $item_2->sub_basket[] = $sub_item_2_3;
    }

    // Add item to the main basket
    $total += $sub_total_2;
    $item_2->total = $sub_total_2;
    $payment->basket[] = $item_2;
}


// Create the payment transaction
try {
    $payment->amount = $total;
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

Created secupay payment transaction with id: yfgocvvnlzex2937888
Payment data: SecucardConnect\Product\Payment\Model\SecupayPrepay Object
(
    [transfer_purpose] => TA 12351788
    [transfer_account] => SecucardConnect\Product\Payment\Model\TransferAccount Object
        (
            [account_owner] => secupay AG
            [accountnumber] => 1747013
            [iban] => DE88300500000001747013
            [bic] => WELADEDDXXX
            [bankcode] => 30050000
        )

    [contract] =>
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] => DateTime Object
                (
                    [date] => 2018-06-15 14:03:04.000000
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
                    [id] => PCR_WZQJUBY092MP3JZ72F9VH9DTKGFEAH
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
            [id] => PCU_3FF6BV4FU2MP3YBS2F9VHB043H52A2
            [object] => payment.customers
        )

    [recipient] =>
    [amount] => 2900
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 2018000123
    [trans_id] => 12351788
    [status] => authorized
    [transaction_status] => 25
    [basket] => Array
        (
            [0] => stdClass Object
                (
                    [item_type] => sub_transaction
                    [name] => Position 1 aus Bestellung 000x
                    [total] => 1900
                    [apikey] => dd0d86a14ea00c14888b48e408851bac8d7a44de
                    [reference_id] => 52534
                    [sub_basket] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [item_type] => article
                                    [quantity] => 2
                                    [name] => Position 1 Artikel A
                                    [ean] => EAN001
                                    [tax] => 19
                                    [total] => 1800
                                    [price] => 900
                                    [reference_id] => 52534.1
                                )

                            [1] => stdClass Object
                                (
                                    [item_type] => article
                                    [quantity] => 2
                                    [name] => Position 1 Artikel AA
                                    [ean] => EAN011
                                    [tax] => 19
                                    [total] => 100
                                    [price] => 50
                                    [reference_id] => 52534.2
                                )

                            [2] => stdClass Object
                                (
                                    [item_type] => stakeholder_payment
                                    [name] => Position 1 plattform provision
                                    [total] => 200
                                    [apikey] => 3e48bb4c1cf162c644a1f10d7e86bb83a3b1788d
                                    [reference_id] => 52534.3
                                )

                            [3] => stdClass Object
                                (
                                    [item_type] => stakeholder_payment
                                    [name] => Position 1 merchant provision
                                    [total] => 100
                                    [apikey] => 86ccab3909c993efcad51b918fc2479827940f33
                                    [reference_id] => 52534.4
                                )

                        )

                )

            [1] => stdClass Object
                (
                    [item_type] => sub_transaction
                    [name] => Position 2 aus Bestellung 000x
                    [total] => 1000
                    [apikey] => dd0d86a14ea00c14888b48e408851bac8d7a44de
                    [reference_id] => 52535
                    [sub_basket] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [item_type] => article
                                    [quantity] => 1
                                    [name] => Position 2 Artikel A
                                    [ean] => EAN002
                                    [tax] => 19
                                    [total] => 1000
                                    [price] => 1000
                                    [reference_id] => 52535.1
                                )

                            [1] => stdClass Object
                                (
                                    [item_type] => stakeholder_payment
                                    [name] => Position 2 plattform provision
                                    [total] => 200
                                    [apikey] => 3e48bb4c1cf162c644a1f10d7e86bb83a3b1788d
                                    [reference_id] => 52535.2
                                )

                            [2] => stdClass Object
                                (
                                    [item_type] => stakeholder_payment
                                    [name] => Position 2 merchant provision
                                    [total] => 100
                                    [apikey] => 86ccab3909c993efcad51b918fc2479827940f33
                                    [reference_id] => 52535.3
                                )

                        )

                )

        )

    [experience] =>
    [accrual] => 1
    [subscription] =>
    [redirect_url] => SecucardConnect\Product\Payment\Model\RedirectUrl Object
        (
            [url_success] => http://shop.example.com/success.php
            [url_failure] => http://shop.example.com/failure.php
            [iframe_url] => https://api-dev6.secupay-ag.de/payment/yfgocvvnlzex2937888
            [url_push] =>
        )

    [url_success] =>
    [url_failure] =>
    [iframe_url] => https://api-dev6.secupay-ag.de/payment/yfgocvvnlzex2937888
    [opt_data] =>
    [payment_action] => sale
    [used_payment_instrument] =>
    [sub_transactions] => Array
        (
            [0] => stdClass Object
                (
                    [id] => yfgocvvnlzex2937888_12351789
                    [trans_id] => 12351789
                    [reference_id] => 52534
                )

            [1] => stdClass Object
                (
                    [id] => yfgocvvnlzex2937888_12351794
                    [trans_id] => 12351794
                    [reference_id] => 52535
                )

        )

    [id] => yfgocvvnlzex2937888
    [object] => payment.secupayprepays
)

 */
