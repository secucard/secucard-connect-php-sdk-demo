<?php

include "lib/init.php";


$app->map('/', function () use ($app, $secucard) {

    $new = null;

    if ($app->request->isPost()) {

        $contact_data = array(
            'salutation' => $app->request->post('salutation'),
            'forename' => $app->request->post('forename'),
            'surname' => $app->request->post('surname'),
            'dob' => $app->request->post('dob'),
            'birthplace' => $app->request->post('birthplace'),
            'nationality' => "de",
            'email' => 'test@example.com',
            'phone' => '+4935955755050',
            'address' => array(
                'street' => $app->request->post('street'),
                'street_number' => $app->request->post('street_number'),
                'city' => $app->request->post('city'),
                'country' => $app->request->post('country'),
                'postal_code' => $app->request->post('postal_code'),
            )
        );

        $identrequestPerson = $secucard->factory('Services\IdentrequestsPerson');

        $identrequestPerson->contact = $contact_data;
        $identrequestPerson->custom1 = 'custom_test';

        $identrequest = new secucard\models\Services\Identrequests($secucard);
        $identrequest->owner_transaction_id = 'tx_32874283492098479';
        $identrequest->type = 'person';
        $identrequest->demo = true;

        $identrequest->addRelated('person', $identrequestPerson);

        $new = $identrequest->save();
    }

    #$list = $secucard->services->identresults->getList(array());

    $list = null;
    $app->render('request.twig', array('new' => $new, 'list' => $list));

})->via('GET', 'POST')->name('request');


$app->map('/result(/:id)', function ($id = false) use ($app, $secucard) {
    $app->render('result.twig');
})->via('GET', 'POST')->name('result');


$app->map('/setting', function () use ($app, $secucard) {
    $app->render('setting.twig');
})->via('GET', 'POST')->name('setting');

$app->run();