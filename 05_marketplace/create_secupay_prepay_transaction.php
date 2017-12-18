<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Payment\Model\SecupayPrepay;
use SecucardConnect\Product\Payment\Model\RedirectUrl;
use SecucardConnect\Product\Payment\Model\Basket;

/**
 * @var \SecucardConnect\Product\Payment\SecupayPrepaysService $service
 */
$service = $secucard->payment->secupayprepays;

$payment = new SecupayPrepay();
$payment->amount = 10000;    // corresponds 100.00 EUR
$payment->currency = 'EUR';
$payment->purpose = 'Your purpose from TestShopName';
$payment->order_id = '2017000123';
$payment->customer = $customer;
$payment->redirect_url = new RedirectUrl();
$payment->redirect_url->url_success = 'http://shop.example.com/success.php';
$payment->redirect_url->url_failure = 'http://shop.example.com/failure.php';
$payment->redirect_url->url_push = 'http://shop.example.com/push.php';

// Block the payout till the date of delivery (of the product or service)
$payment->accrual = true;

$total = 0;
$payment->basket = [];

/*
 * Define the first sub-transaction
 */
{
    $sub_total_1 = 0;
    $item_1 = new Basket();
    $item_1->contract_id = 'PCR_2TSA6QWY02MHNG9W875XU0PKC6DNA7'; // The contract id of the first merchant/project
    $item_1->item_type = Basket::ITEM_TYPE_SUB_TRANSACTION;
    $item_1->name = 'Position 1 of Order 000x';

    // Add sub basket items
    $item_1->sub_basket = [];
    {
        /*
         * Define the first sub item
         */
        $sub_item_1_1 = new Basket();
        $sub_item_1_1->quantity = 2;
        $sub_item_1_1->name = 'Item A';
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
         * Define the second sub item
         */
        $sub_item_1_2 = new Basket();
        $sub_item_1_2->contract_id = 'PCR_2NSCASA2N2MF75F5875XUDD87M8UA6'; // The contract id of the platform
        $sub_item_1_2->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_1_2->name = 'platform provision';
        $sub_item_1_2->total = 200;

        // Calc sums for the second item
        $sub_total_1 += $sub_item_1_2->total;

        // Add it to the sub basket
        $item_1->sub_basket[] = $sub_item_1_2;
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
    $item_2->contract_id = 'PCR_2TSA6QWY02MHNG9W875XU0PKC6DNA7'; // The contract id of the first merchant/project
    $item_2->item_type = Basket::ITEM_TYPE_SUB_TRANSACTION;
    $item_2->name = 'Position 2 of Order 000x';

    // Add sub basket items
    $item_2->sub_basket = [];
    {
        /*
         * Define the first sub item
         */
        $sub_item_2_1 = new Basket();
        $sub_item_2_1->quantity = 1;
        $sub_item_2_1->name = 'Item B';
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
         * Define the second sub item
         */
        $sub_item_2_2 = new Basket();
        $sub_item_2_2->contract_id = 'PCR_2NSCASA2N2MF75F5875XUDD87M8UA6'; // The contract id of the platform
        $sub_item_2_2->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_2_2->name = 'platform provision';
        $sub_item_2_2->total = 100;

        // Calc sums for the second item
        $sub_total_2 += $sub_item_2_2->total;

        // Add it to the sub basket
        $item_2->sub_basket[] = $sub_item_2_2;
    }

    // Add item to the main basket
    $total += $sub_total_2;
    $item_2->total = $sub_total_2;
    $payment->basket[] = $item_2;
}

/*
 * Define the third sub-transaction
 */
{
    $sub_total_3 = 0;
    $item_3 = new Basket();
    $item_3->contract_id = 'PCR_3XDEP6RBA2MHNGBV875XUBTKC6DNA7'; // The contract id of the second merchant/project
    $item_3->item_type = Basket::ITEM_TYPE_SUB_TRANSACTION;
    $item_3->name = 'Position 3 of Order 000x';

    // Add sub basket items
    $item_3->sub_basket = [];
    {
        /*
         * Define the first sub item
         */
        $sub_item_3_1 = new Basket();
        $sub_item_3_1->quantity = 1;
        $sub_item_3_1->name = 'Item D';
        $sub_item_3_1->ean = 'EAN004';
        $sub_item_3_1->tax = 19;
        $sub_item_3_1->price = 4500;
        $sub_item_3_1->item_type = Basket::ITEM_TYPE_ARTICLE;

        // Calc sums for the first item
        $sub_item_3_1->total = $sub_item_3_1->quantity * $sub_item_3_1->price;
        $sub_total_3 += $sub_item_3_1->total;

        // Add it to the sub basket
        $item_3->sub_basket[] = $sub_item_3_1;

        /*
         * Define the second sub item
         */
        $sub_item_3_2 = new Basket();
        $sub_item_3_2->contract_id = 'PCR_2NSCASA2N2MF75F5875XUDD87M8UA6'; // The contract id of the platform
        $sub_item_3_2->item_type = Basket::ITEM_TYPE_STAKEHOLDER_PAYMENT;
        $sub_item_3_2->name = 'platform provision';
        $sub_item_3_2->total = 500;

        // Calc sums for the second item
        $sub_total_3 += $sub_item_3_2->total;

        // Add it to the sub basket
        $item_3->sub_basket[] = $sub_item_3_2;
    }

    // Add item to the main basket
    $total += $sub_total_3;
    $item_3->total = $sub_total_3;
    $payment->basket[] = $item_3;
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

Created secupay payment transaction with id: nmmztmejxbik2409412
Payment data: SecucardConnect\Product\Payment\Model\SecupayPrepay Object
(
    [transfer_purpose] => TA 10900036
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
                    [date] => 2017-12-18 10:28:49.000000
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
                    [id] => PCR_2NSCASA2N2MF75F5875XUDD87M8UA6
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
            [id] => PCU_2U6M42UE32MHNH98X75XUQ5MC6DNA7
            [object] => payment.customers
        )

    [recipient] =>
    [amount] => 8100
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 2017000123
    [trans_id] => 10900036
    [status] => authorized
    [transaction_status] => 25
    [basket] => Array
        (
            [0] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] =>
                    [name] => Position 1 of Order 000x
                    [ean] =>
                    [tax] =>
                    [total] => 2000
                    [price] =>
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => article
                    [apikey] => 2f1fdbf156cc8301544dc70faf4268ec4202d5b5
                    [sub_basket] => ...
                )

            [1] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] =>
                    [name] => Position 2 of Order 000x
                    [ean] =>
                    [tax] =>
                    [total] => 1100
                    [price] =>
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => article
                    [apikey] => 2f1fdbf156cc8301544dc70faf4268ec4202d5b5
                    [sub_basket] => ...
                )

            [2] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] =>
                    [name] => Position 3 of Order 000x
                    [ean] =>
                    [tax] =>
                    [total] => 5000
                    [price] =>
                    [contract_id] =>
                    [model] =>
                    [article_number] =>
                    [item_type] => article
                    [apikey] => e01b4365254c34d8b8a5a35693a01b64b5f5b6ce
                    [sub_basket] => ...
                )

        )

    [experience] =>
    [accrual] => 1
    [subscription] =>
    [redirect_url] => SecucardConnect\Product\Payment\Model\RedirectUrl Object
        (
            [url_success] => http://shop.example.com/success.php
            [url_failure] => http://shop.example.com/failure.php
            [iframe_url] => https://api-testing.secupay-ag.de/payment/nmmztmejxbik2409412
            [url_push] =>
        )

    [url_success] =>
    [url_failure] =>
    [iframe_url] => https://api-testing.secupay-ag.de/payment/nmmztmejxbik2409412
    [opt_data] =>
    [payment_action] => sale
    [used_payment_instrument] =>
    [id] => nmmztmejxbik2409412
    [object] => payment.secupayprepays
)

 */