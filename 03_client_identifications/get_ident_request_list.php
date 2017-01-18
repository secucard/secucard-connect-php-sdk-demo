<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Services\IdentRequestsService $service
 */
$service = $secucard->services->identrequests;

// You may obtain a global list of available containers
$identrequests = $service->getList();

if ($identrequests === null) {
	throw new Exception("No identification requests found.");
}

print_r($identrequests);

/*
 * If you have many identification requests, you would need following code to get them all:
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



####### C:\projects\secucard-connect-php-sdk-demo\03_client_identifications\get_ident_request_list.php #######

SecucardConnect\Product\Common\Model\BaseCollection Object
(
    [items] => Array
        (

            [0] => SecucardConnect\Product\Services\Model\IdentRequest Object
                (
                    [type] => company
                    [status] => requested
                    [owner] =>
                    [owner_transaction_id] => tx_1234567811
                    [created] => DateTime Object
                        (
                            [date] => 2017-01-18 10:58:54.000000
                            [timezone_type] => 1
                            [timezone] => +00:00
                        )

                    [contract] => SecucardConnect\Product\Services\Model\Contract Object
                        (
                            [redirect_url_success] =>
                            [redirect_url_failed] =>
                            [push_url] =>
                            [created] =>
                            [merchant] =>
                            [id] => SIC_K8BAH7Y662M3XG39X75XUN85FK2JA9
                            [object] => services.identcontracts
                        )

                    [person] => Array
                        (
                            [0] => SecucardConnect\Product\Services\Model\RequestPerson Object
                                (
                                    [transacion_id] =>
                                    [redirect_url] => https://core-testing.secupay-ag.de/app.core.connector/connect/idents/type/request?id=17670cb850785f
                                    [status] => requested
                                    [owner_transaction_id] =>
                                    [contact] => SecucardConnect\Product\Common\Model\Contact Object
                                        (
                                            [salutation] => Herr
                                            [title] =>
                                            [forename] => Max
                                            [surname] => Musterman3
                                            [name] => Max Musterman3
                                            [companyname] =>
                                            [dob] => DateTime Object
                                                (
                                                    [date] => 1996-11-23 00:00:00.000000
                                                    [timezone_type] => 1
                                                    [timezone] => +01:00
                                                )

                                            [birthplace] => Dresden
                                            [nationality] => DE
                                            [gender] =>
                                            [phone] => +4935955755050
                                            [mobile] => +4935955755050
                                            [email] => example@example.com
                                            [picture] =>
                                            [pictureObject] =>
                                            [url_website] =>
                                            [address] => SecucardConnect\Product\Common\Model\Address Object
                                                (
                                                    [street] => Goethestr.
                                                    [street_number] => 6
                                                    [city] => Pulsnitz
                                                    [postal_code] => 01896
                                                    [country] => DE
                                                    [id] =>
                                                    [object] =>
                                                )

                                        )

                                    [custom1] => custom1
                                    [custom2] => 12345
                                    [custom3] =>
                                    [custom4] =>
                                    [custom5] =>
                                )

                        )

                    [id] => SIR_3EBFDQESF2M3V909X75XUT95UCA7AN
                    [object] => services.identrequests
                )

            [1] => SecucardConnect\Product\Services\Model\IdentRequest Object
                (
                    [type] => company
                    [status] => ok
                    [owner] =>
                    [owner_transaction_id] => tx_1234567890
                    [created] => DateTime Object
                        (
                            [date] => 2017-01-18 11:31:57.000000
                            [timezone_type] => 1
                            [timezone] => +00:00
                        )

                    [contract] => SecucardConnect\Product\Services\Model\Contract Object
                        (
                            [redirect_url_success] =>
                            [redirect_url_failed] =>
                            [push_url] =>
                            [created] =>
                            [merchant] =>
                            [id] => SIC_K8BAH7Y662M3XG39X75XUN85FK2JA9
                            [object] => services.identcontracts
                        )

                    [person] => Array
                        (
                            [0] => SecucardConnect\Product\Services\Model\RequestPerson Object
                                (
                                    [transacion_id] =>
                                    [redirect_url] => https://core-testing.secupay-ag.de/app.core.connector/connect/idents/type/request?id=ab537e6c097f2a
                                    [status] => ok
                                    [owner_transaction_id] =>
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
                                            [mobile] => 0049-123456789
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
                                                    [country] => DEUTSCHLAND
                                                    [id] =>
                                                    [object] =>
                                                )

                                        )

                                    [custom1] => custom1
                                    [custom2] => 12345
                                    [custom3] =>
                                    [custom4] =>
                                    [custom5] =>
                                )

                        )

                    [id] => SIR_3HBZN37US2M3VA8MX75XURY9UCA7AN
                    [object] => services.identrequests
                )

        )

    [scrollId] =>
    [totalCount] => 2
    [count] => 2
)

 */