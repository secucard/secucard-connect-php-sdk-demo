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