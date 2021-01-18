<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay invoice transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\SecupayInvoice;

/**
 * @var \SecucardConnect\Product\Payment\SecupayInvoicesService $service
 */
$service = $secucard->payment->secupayinvoices;

$invoice = new SecupayInvoice();
$invoice->amount = 100; // Amount in cents (or in the smallest unit of the given currency)
$invoice->currency = 'EUR'; // The ISO-4217 code of the currency
$invoice->purpose = 'Your purpose from TestShopName';
$invoice->order_id = '201600123'; // The shop order id
$invoice->customer = $customer;

try {
    $invoice = $service->save($invoice);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($invoice->id) {
    echo 'Created secupay invoice transaction with id: ' . $invoice->id . "\n";
    echo 'Invoice data: ' . print_r($invoice, true) . "\n";
} else {
    echo 'Invoice creation failed' . "\n";
}

/*
 * To cancel the transaction you would use:
 *
// The $contract_id should be null (if the transaction was created by your main contract or the id of cloned contract that was used to create transaction:
$contract_id = empty($contract) null : $contract->id;
$service->cancel($invoice->id, $contract_id));
 */



/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created secupay invoice transaction with id: hcbjqubbeeyw1647097
Invoice data: SecucardConnect\Product\Payment\Model\SecupayInvoice Object
(
    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
        (
            [created] => DateTime Object
                (
                    [date] => 2016-11-28 11:44:47.000000
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
            [id] => PCU_2NXJMDCWV2MWSWAFX75XURW7V9NJAN
            [object] => payment.customers
        )

    [contract] =>
    [amount] => 100
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 201600123
    [trans_id] => 8475282
    [status] => accepted
    [transaction_status] => 85
    [basket] =>
    [id] => hcbjqubbeeyw1647097
    [object] => payment.secupayinvoices
)

 */
