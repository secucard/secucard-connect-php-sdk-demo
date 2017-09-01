<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

$creditcard = $service->get($payment->id);

if ($creditcard === null) {
    throw new Exception("No creditcard transaction found.");
}

print_r($creditcard);




/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

SecucardConnect\Product\Payment\Model\SecupayCreditcard Object
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
    [status] => accepted
    [transaction_status] => 11
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
    [redirect_url] =>
    [url_success] =>
    [url_failure] =>
    [iframe_url] =>
    [opt_data] =>
    [payment_action] => sale
    [used_payment_instrument] => SecucardConnect\Product\Payment\Model\PaymentInstrument Object
        (
            [data] => Array
                (
                    [owner] => John Doe
                    [pan] => 4716 XXXX XXXX 2663
                    [expiration_date] =>
                    [issuer] =>
                )

            [type] => credit_card
        )

    [id] => zhzrmbjotubc2209666
    [object] => payment.secupaycreditcards
)

 */