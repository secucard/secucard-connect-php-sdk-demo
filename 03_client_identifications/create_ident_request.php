<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Common\Model\Address;
use SecucardConnect\Product\Common\Model\Contact;
use SecucardConnect\Product\Services\Model\IdentRequest;
use SecucardConnect\Product\Services\Model\RequestPerson;

/**
 * @var \SecucardConnect\Product\Services\IdentRequestsService $service
 */
$service = $secucard->services->identrequests;

$identrequests = new IdentRequest();
$identrequests->type = IdentRequest::TYPE_COMPANY;   // or TYPE_PERSON
$identrequests->demo = true;
$identrequests->owner_transaction_id = 'tx_1234567890';

$person = new RequestPerson();
$person->custom1 = 'custom1';
$person->custom2 = '12345';
$person->custom3 = null;
$person->custom4 = null;
$person->custom5 = null;

$contact = new Contact();
$contact->salutation = 'Herr';
$contact->forename = 'Max';
$contact->surname = 'Musterman3';
$contact->companyname = 'Testfirma UG';
$contact->dob = '1996-11-23';
$contact->birthplace = 'Dresden';
$contact->nationality = 'DE';
// specifying email for customer is important, so the customer can receive Mandate information
$contact->email = 'example@example.com';
$contact->phone = '+4935955755050';

$address = new Address();
$address->street = 'Goethestr.';
$address->street_number = '6';
$address->city = 'Pulsnitz';
$address->country = 'DE';
$address->postal_code = '01896';

$contact->address = $address;
$person->contact = $contact;
$identrequests->person[] = $person;


try {
	$identrequests = $service->save($identrequests);
} catch (\Exception $e) {
	echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($identrequests->id) {
	echo 'Created identification request with id: ' . $identrequests->id . "\n";
	echo 'Identification request data: ' . print_r($identrequests, true) . "\n";
} else {
	echo 'Customer creation failed';
}


/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created identification request with id: SIR_A804VHQFY2M3VAB4X75XUEYAECA7AN
Identification request data: SecucardConnect\Product\Services\Model\IdentRequest Object
(
    [type] => company
    [status] => requested
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
                    [status] => requested
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