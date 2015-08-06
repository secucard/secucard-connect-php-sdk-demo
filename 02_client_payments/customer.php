<?php

require_once __DIR__ . '/lib/init.php';

// new customer creation:
$contact = [
    'salutation' => 'Mr.',
    'title' => 'Dr.',
    'forename' => 'John',
    'surname' => 'Doe',
    'companyname' => 'Example Inc.',
    'dob' => '1901-02-03',
    'email' => 'example@example.com',
    'phone' => '0049-123-456789',
    'mobile' => '0049-987-654321',
    'address' => [
        'street' => 'Example Street',
        'street_number' => '6a',
        'postal_code' => '01234',
        'city' => 'Examplecity',
        'country' => 'Germany',
]];
$customer_data = ['contact' => $contact,];

$customer = $secucard->factory('Payment\Customers');
$logger->debug('created object');

$customer->initValues($customer_data);
$logger->debug('object data initialized');

$success = false;
try {
    $success = $customer->save();
} catch (\GuzzleHttp\Exception\TransferException $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        if ($e->getResponse()->getBody()) {
            echo 'Body: ' . json_encode($e->getResponse()->getBody()->__toString()) . "\n";
        }
    }
} catch (Exception $e) {
    echo 'Error message: '. $e->getMessage() . "\n";
}

if ($success) {
    echo 'Created Customer with id: ' . $customer->id . "\n";
    echo 'Customer data: ' . $customer->as_json() . "\n";
} else {
    echo 'Customer creation failed';
}