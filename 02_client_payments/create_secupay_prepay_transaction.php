<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay prepay transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\SecupayPrepay;

/**
 * @var \SecucardConnect\Product\Payment\SecupayPrepaysService $service
 */
$service = $secucard->payment->secupayprepays;

$prepay = new SecupayPrepay();
$prepay->amount = 100; // Amount in cents (or in the smallest unit of the given currency)
$prepay->currency = 'EUR'; // The ISO-4217 code of the currency
$prepay->purpose = 'Your purpose from TestShopName';
$prepay->order_id = '201600123'; // The shop order id
$prepay->customer = $customer;

// if you want to create prepay payment for a cloned contract (contract that you created by cloning main contract)
/*
$contract = new Contract();
$contract->id = 'PCR_XXXX';
$contract->object = 'payment.contracts';
$prepay->contract = $contract;
*/

try {
    $prepay = $service->save($prepay);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($prepay->id) {
    echo 'Created secupay prepay transaction with id: ' . $prepay->id . "\n";
    echo 'Prepay data: ' . print_r($prepay, true) . "\n";
} else {
    echo 'Prepay creation failed' . "\n";
}

/*
 * To cancel the transaction you would use:
 *
// The $contract_id should be null (if the transaction was created by your main contract or the id of cloned contract that was used to create transaction:
$contract_id = empty($contract) null : $contract->id;
$service->cancel($prepay->id, $contract_id));
 */



/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created secupay prepay transaction with id: ufgjjwdfmwph1468032
Prepay data: SecucardConnect\Product\Payment\Model\SecupayPrepay Object
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

    [transfer_purpose] => TA 7867851
    [transfer_account] => SecucardConnect\Product\Payment\Model\TransferAccount Object
        (
            [account_owner] => secupay AG
            [accountnumber] => 1747013
            [iban] => DE88300500000001747013
            [bic] => WELADEDDXXX
            [bankcode] => 30050000
        )

    [contract] =>
    [amount] => 100
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 201600123
    [trans_id] => 7867851
    [status] => authorized
    [transaction_status] => 25
    [id] => ufgjjwdfmwph1468032
    [object] => payment.secupayprepays
)

 */