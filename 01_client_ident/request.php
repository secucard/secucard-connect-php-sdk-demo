<?php

include 'lib/init.php';

// new request creation:
$contact_data = array(
    'salutation' => 'Herr',
    'forename' => 'Max',
    'surname' => 'Mustermann',
    'dob' => '1975-03-21',
    'birthplace' => 'Dresden',
    'nationality' => 'de',
    'email' => 'test@example.com',
    'phone' => '+4935955755050',
    'address' => array(
        'street' => 'Musterstr.',
        'street_number' => '1',
        'city' => 'Mustercity',
        'country' => 'DE',
        'postal_code' => '01234'
    ),
);

$identrequestPerson = $secucard->factory('Services\IdentrequestsPerson');

$identrequestPerson->contact = $contact_data;
$identrequestPerson->custom1 = 'custom_test';

$identrequest = new secucard\models\Services\Identrequests($secucard);
$identrequest->owner_transaction_id = 'tx_32874283492098479';
$identrequest->type = 'person';
$identrequest->demo = true;

$identrequest->addRelated('person', $identrequestPerson);

$identrequest->save();


echo 'Created identrequest with id: ' . $identrequest->id;
echo "\n\n";
foreach ($identrequest->person as $pers) {
    #var_dump($pers);
    echo 'URL: ' . $pers->redirect_url . "\n";
}