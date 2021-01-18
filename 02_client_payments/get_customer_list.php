<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var  \SecucardConnect\Product\Payment\CustomersService $service
 */
$service = $secucard->payment->customers;

// You may obtain a global list of available customers
$customers = $service->getList();

if ($customers === null) {
    throw new Exception("No Customers found.");
}

print_r($customers);

/*
 * If you have many customers, you would need following code to get them all:
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
            [0] => SecucardConnect\Product\Payment\Model\Customer Object
                (
                    [created] => DateTime Object
                        (
                            [date] => 2016-09-30 09:33:59.000000
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
                                    [country] => DE
                                    [id] =>
                                    [object] =>
                                )

                        )

                    [merchant] =>
                    [id] => PCU_VNW4PUUF22YVE2YJX75XU6GRM8QGAE
                    [object] => payment.customers
                )

        )

    [scrollId] =>
    [totalCount] => 32
    [count] => 1
)

 */
