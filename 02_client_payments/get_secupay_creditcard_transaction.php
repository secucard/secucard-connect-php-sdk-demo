<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Payment\SecupayCreditcardsService $service
 */
$service = $secucard->payment->secupaycreditcards;

// You may obtain a global list of available containers
$creditcard = $service->get('wxnxezyakawp1647225');

if ($creditcard === null) {
    throw new Exception("No creditcard transaction found.");
}

print_r($creditcard);

/*
 * If you have many containers, you would need following code to get them all:
 *
$expiration_time = '5m';
$items = [];
$list = $service->getScrollableList([], $expiration_time);
while (count($list) != 0) {
    $items = array_merge($items, $list->items);
    $list = $service->getNextBatch($list->scrollId);
}
 */




/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

SecucardConnect\Product\Common\Model\BaseCollection Object
(
    [items] => Array
        (
            [0] => SecucardConnect\Product\Payment\Model\Container Object
                (
                    [customer] => SecucardConnect\Product\Payment\Model\Customer Object
                        (
                            [created] =>
                            [updated] =>
                            [contract] =>
                            [contact] =>
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

        )

    [scrollId] =>
    [totalCount] => 10
    [count] => 1
)

 */