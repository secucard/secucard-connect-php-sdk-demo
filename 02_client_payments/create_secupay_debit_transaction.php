<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/*
 * NOTE: It is not allowed to list all already created secupay debit transactions, so you should store created ids.
 */

use SecucardConnect\Product\Payment\Model\SecupayDebit;
use SecucardConnect\Product\Payment\Model\Basket;

/**
 * @var \SecucardConnect\Product\Payment\SecupayDebitsService $service
 */
$service = $secucard->payment->secupaydebits;

$debit = new SecupayDebit();
$debit->amount = 245; // Amount in cents (or in the smallest unit of the given currency)
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

// Create basket

// Add the first item
$item_1 = new Basket();
$item_1->article_number = '3211';
$item_1->ean = '4123412341243';
$item_1->item_type = 'article';
$item_1->name = 'Testname 1';
$item_1->price = 25;
$item_1->quantity = 2;
$item_1->tax = 19;
$item_1->total = 50;
$debit->basket[] = $item_1;

// Add the shipping costs
$shipping = new Basket();
$shipping->item_type = 'shipping';
$shipping->name = 'Deutsche Post Warensendung';
$shipping->tax = 19;
$shipping->total = 145;
$debit->basket[] = $shipping;

// add contract
/*
$contract1 = new Basket();
$contract1->contract = $contract;
$debit->basket[] = $contract1;
$shipping->name = 'Geld für Projekt-Starter';
$shipping->total = 145;
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

Created secupay debit transaction with id: yetymazqrhqd1647092
Debit data: SecucardConnect\Product\Payment\Model\SecupayDebit Object
(
    [container] => SecucardConnect\Product\Payment\Model\Container Object
        (
            [customer] => SecucardConnect\Product\Payment\Model\Customer Object
                (
                    [created] => DateTime Object
                        (
                            [date] => 2016-11-23 11:55:58.000000
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
                                    [country] => Deutschland
                                    [id] =>
                                    [object] =>
                                )

                        )

                    [merchant] =>
                    [id] => PCU_2XCPZ6EGY2MWYEMV875XUVDZ7M8UA6
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

            [type] =>
            [created] => DateTime Object
                (
                    [date] => 2016-11-23 11:55:58.000000
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

            [id] => PCT_WYH0YXDB52MWYEMV875XUVDZ7M8UA7
            [object] => payment.containers
        )

    [contract] =>
    [amount] => 245
    [currency] => EUR
    [purpose] => Your purpose from TestShopName
    [order_id] => 201600123
    [trans_id] => 0
    [status] => internal_server_status
    [transaction_status] =>
    [basket] => Array
        (
            [0] => SecucardConnect\Product\Payment\Model\Basket Object
                (
                    [quantity] => 2
                    [name] => Testname 1
                    [ean] => 4123412341243
                    [tax] => 19
                    [total] => 50
                    [price] => 25
                    [contract_id] =>
                    [model] =>
                    [article_number] => 3211
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

        )

    [id] => yetymazqrhqd1647092
    [object] => payment.secupaydebits
)

 */
