<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * THIS IS SAMPLE command line script to simulate event push from backend
 *
 * The script registers callback handler for event on SecucardConnect object, then
 * it creates its own sample $raw_event_data (normally the event data would come from the server to url_push), then
 * it calls the processPush() method on SecucardConnect object that processes the pushed data
 * and finally it calls the registered handler with the data of changed object.
 */

// Register function to handle new/changed objects
$secucard->payment->secupaydebits->onSecupayDebitChanged(function ($obj) {
    // function is really simple, just print the updated object with data
    print_r($obj);
});

// Simulate sample push data
// TODO You should set correct the target, object and id fields
$raw_event_data = '{
    "object":"event.pushes",
    "id":"EVT_123456789",
    "target":"payment.secupaydebits",
    "type":"changed",
    "data":[
        {
            "object":"payment.secupaydebits",
            "id":"ctgvwjoypzyj2052244"
        }
    ]
}';

/*
 * If the data would be posted to the current script (depends on your url_push configuration for the contract),
 * you can use following code to get posted data:
 *
$raw_event_data = file_get_contents("php://input");
 */

try {
    $secucard->handleEvent($raw_event_data);
} catch (Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}


/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

SecucardConnect\Product\Payment\Model\SecupayDebit Object
(
    [container] => SecucardConnect\Product\Payment\Model\Container Object
        (
            [customer] => SecucardConnect\Product\Payment\Model\Customer Object
                (
                    [created] => DateTime Object
                        (
                            [date] => 2016-10-14 11:49:29.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [updated] =>
                    [contract] => SecucardConnect\Product\Payment\Model\Contract Object
                        (
                            [created] =>
                            [updated] =>
                            [parent] =>
                            [merchant] =>
                            [allow_cloning] =>
                            [sepa_mandate_inform] =>
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
                                    [country] => Deutschland
                                    [id] =>
                                    [object] =>
                                )

                        )

                    [merchant] =>
                    [id] => PCU_M0PSEHCWK2M00Y8KX75XUMGS6W8XAQ
                    [object] => payment.customers
                )

            [public] => SecucardConnect\Product\Payment\Model\Data Object
                (
                    [owner] => Max Mustermann
                    [iban] => DE62100208900001317270
                    [bic] => HYVEDEMM488
                    [bankname] => UniCredit Bank - HypoVereinsbank
                )

            [private] => SecucardConnect\Product\Payment\Model\Data Object
                (
                    [owner] => Max Mustermann
                    [iban] => DE62100208900001317270
                    [bic] => HYVEDEMM488
                    [bankname] => UniCredit Bank - HypoVereinsbank
                )

            [assign] =>
            [type] =>
            [created] => DateTime Object
                (
                    [date] => 2016-10-14 11:49:29.000000
                    [timezone_type] => 1
                    [timezone] => +02:00
                )

            [updated] =>
            [contract] => SecucardConnect\Product\Payment\Model\Contract Object
                (
                    [created] =>
                    [updated] =>
                    [parent] =>
                    [merchant] =>
                    [allow_cloning] =>
                    [sepa_mandate_inform] =>
                    [id] => PCR_W6AV7JJUJ2YS6WHFR5GQGS99ABZDAP
                    [object] => payment.contracts
                )

            [id] => PCT_3PQDC8BX82M00Y8KX75XUMGS6W8XAR
            [object] => payment.containers
        )

    [contract] =>
    [amount] => 100
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 201600123
    [trans_id] => 7867850
    [status] => accepted
    [transaction_status] => 11
    [id] => irsuobfjbrui1468031
    [object] => payment.secupaydebits
)

 */