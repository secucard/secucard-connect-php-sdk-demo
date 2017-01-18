<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

/**
 * @var \SecucardConnect\Product\Services\IdentRequestsService $service
 */
$service = $secucard->services->identrequests;

$identrequest = $service->get('SIR_A804VHQFY2M3VAB4X75XUEYAECA7AN');

if ($identrequest === null) {
	throw new Exception("No identification request found.");
}

print_r($identrequest);




/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

SecucardConnect\Product\Services\Model\IdentRequest Object
(
    [type] => company
    [status] => ok
    [owner] =>
    [owner_transaction_id] => tx_1234567890
    [created] => DateTime Object
        (
            [date] => 2017-01-18 11:34:33.000000
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
                    [redirect_url] => https://core-testing.secupay-ag.de/app.core.connector/connect/idents/type/request?id=c70012f96d8fcf
                    [status] => ok
                    [owner_transaction_id] =>
                    [contact] => SecucardConnect\Product\Common\Model\Contact Object
                        (
                            [salutation] => Herr
                            [title] =>
                            [forename] => Max
                            [surname] => Musterman3
                            [name] => Max Musterman3
                            [companyname] => Testfirma UG
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

    [id] => SIR_A804VHQFY2M3VAB4X75XUEYAECA7AN
    [object] => services.identrequests
)

 */