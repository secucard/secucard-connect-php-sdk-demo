<?php
echo chr(10).chr(10).'####### ' . __FILE__ . ' #######'.chr(10).chr(10);

use SecucardConnect\Product\Common\Model\Address;
use SecucardConnect\Product\Common\Model\Contact;
use SecucardConnect\Product\Payment\Model\Customer;

/**
 * @var \SecucardConnect\Product\Payment\CustomersService $service
 */
$service = $secucard->payment->customers;

$contact = new Contact();
$contact->salutation = 'Mr.';
$contact->title = 'Dr.';
$contact->forename = 'John';
$contact->surname = 'Doe';
$contact->companyname = 'Testfirma';
$contact->dob = '1971-02-03';
$contact->birthplace = 'MyBirthplace';
$contact->nationality = 'DE';
// specifying email for customer is important, so the customer can receive Mandate information
$contact->email = 'example@example.com';
$contact->phone = '0049-123456789';

$address = new Address();
$address->street = 'Example Street';
$address->street_number = '6a';
$address->city = 'ExampleCity';
$address->country = 'Deutschland';
$address->postal_code = '01234';

$contact->address = $address;

$customer = new Customer();
$customer->contact = $contact;

try {
    $customer = $service->save($customer);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($customer->id) {
    echo 'Created Customer with id: ' . $customer->id . "\n";
    echo 'Customer data: ' . print_r($customer, true) . "\n";
} else {
    echo 'Customer creation failed';
}


/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created Customer with id: PCU_M0PSEHCWK2M00Y8KX75XUMGS6W8XAQ
Customer data: SecucardConnect\Product\Payment\Model\Customer Object
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

 */