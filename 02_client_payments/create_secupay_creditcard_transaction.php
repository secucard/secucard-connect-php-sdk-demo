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
$creditcard->customer = $customer;
// The customer will be redirected to "url_success" after you (the shop) has show him the iframe
// and he has filled out the form in this iframe.
// The url of this iframe will be returned in the response of this save request in the variable called "iframe_url".
$creditcard->url_success = 'http://shop.example.com/success.php';
// The customer will be redirected to "url_failure" if we don't accept him for credit card payments.
// You should offer him to pay with other payment methods on this page.
$creditcard->url_failure = 'http://shop.example.com/failure.php';

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
}

/*
 * To cancel the transaction you would use:
$service->cancel($creditcard->id));
 */



/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created secupay creditcard transaction with id: tjftokgadmln1647246
Creditcard data: SecucardConnect\Product\Payment\Model\SecupayCreditcard Object
(
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] => DateTime Object
                (
                    [date] => 2016-12-01 09:24:22.000000
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
                    [id] => PCR_W6AV7JJUJ2YS6WHFR5GQGS99ABZDAP
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

                )

            [merchant] =>
            [id] => PCU_3RTDR52JG2MWVRXR875XU808V9NJAN
            [object] => payment.customers
        )

    [url_success] => http://shop.example.com/success.php
    [url_failure] => http://shop.example.com/failure.php
    [iframe_url] => https://api-testing.secupay-ag.de/payment/tjftokgadmln1647246
    [contract] =>
    [amount] => 100
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 201600123
    [trans_id] => 0
    [status] => internal_server_status
    [transaction_status] =>
    [basket] =>
    [id] => tjftokgadmln1647246
    [object] => payment.secupaycreditcards
)

 */
