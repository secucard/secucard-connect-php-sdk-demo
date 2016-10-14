<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay debit transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\SecupayDebit;

/**
 * @var \SecucardConnect\Product\Payment\SecupayDebitsService $service
 */
$service = $secucard->payment->secupaydebits;

$debit = new SecupayDebit();
$debit->amount = 100; // Amount in cents (or in the smallest unit of the given currency)
$debit->currency = 'EUR'; // The ISO-4217 code of the currency
$debit->purpose = 'Your purpose from TestShopName';
$debit->order_id = '201600123'; // The shop order id
$debit->customer = $customer;
$debit->container = $container;

// if you want to create debit payment for a cloned contract (contract that you created by cloning main contract)
/*
$contract = new Contract();
$contract->id = 'PCR_XXXX';
$contract->object = 'payment.contracts';
$debit->contract = $contract;
*/

try {
    $debit = $service->save($debit);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($debit->id) {
    echo 'Created secupay debit transaction with id: ' . $debit->id . "\n";
    echo 'Debit data: ' . print_r($debit, true) . "\n";
} else {
    echo 'Debit creation failed' . "\n";
}

/*
 * To cancel the transaction you would use:
 *
// The $contract_id should be null (if the transaction was created by your main contract or the id of cloned contract that was used to create transaction:
$contract_id = empty($contract) null : $contract->id;
$service->cancel($debit->id, $contract_id));
 */



/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created secupay debit transaction with id: irsuobfjbrui1468031
Debit data: SecucardConnect\Product\Payment\Model\SecupayDebit Object
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
    [status] => internal_server_status
    [transaction_status] => 1
    [id] => irsuobfjbrui1468031
    [object] => payment.secupaydebits
)

 */