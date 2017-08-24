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
    exit;
}


/*
 * =======================
 * #   SAMPLE RESPONSE   #
 * =======================
 *

Created Customer with id: PCU_3WFU33T2W2MCUM8EX75XU4TSX4JBAE
Customer data: SecucardConnect\Product\Payment\Model\Customer Object
(
    [created] => DateTime Object
        (
            [date] => 2017-08-24 13:01:49.000000
            [timezone_type] => 1
            [timezone] => +02:00
        )

    [updated] =>
    [contract] => SecucardConnect\Product\Payment\Model\Contract Object
        (
            [created] =>
            [updated] =>
            [parent] =>
            [allow_cloning] =>
            [id] => PCR_2C0S37QHH2MASN9V875XU3YFNM8UA6
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
    [merchant_customer_id] =>
    [id] => PCU_3WFU33T2W2MCUM8EX75XU4TSX4JBAE
    [object] => payment.customers
)

 */