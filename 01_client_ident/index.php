<?php

include "lib/init.php";


/*
 * Register helper for auth check
 */
class toolClass {
    public function authCheck($app, $secucard)
    {
        try {
            $list = $secucard->services->identresults->getList(array());
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

}

$app->tool = new toolClass();

/*
 * Create Identrequest
 */
$app->map('/', function () use ($app, $secucard) {

    // Auth check
    if (!$app->tool->authCheck($app, $secucard)) {
        $app->render('error.twig');
        $app->stop();
    }

    $new = null;

    /*
     * Save?
     */
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

    /*
     * Load list
     */
    #$list = $secucard->services->identresults->getList(array());

    $list = null;

    // Render view
    $app->render('request.twig', array('new' => $new, 'list' => $list));

})->via('GET', 'POST')->name('request');


/*
 * Show Identresult
 */
$app->map('/result(/:id)', function ($id = false) use ($app, $secucard) {

    // Auth check
    if (!$app->tool->authCheck($app, $secucard)) {
        $app->render('error.twig');
        $app->stop();
    }

    $app->render('result.twig');
})->via('GET', 'POST')->name('result');


/*
 * Auth settings
 */
$app->map('/setting', function () use ($app, $secucard, $config_sdk) {

    $client_id = $config_sdk['client_id'];
    $client_secret = $config_sdk['client_secret'];

    /*
     * Save?
     */
    if ($app->request->isPost()) {

        $credentials = array(
            'client_id' => $app->request->post('client_id'),
            'client_secret' => $app->request->post('client_secret')
        );

        // overwrite
        $client_id = $credentials['client_id'];
        $client_secret = $credentials['client_secret'];

        // Save to cookie
        $app->setCookie('secucard-connect-demo', json_encode($credentials), '2 days');
    }

    // Render view
    $app->render('setting.twig', array('client_id' => $client_id, 'client_secret' => $client_secret));

})->via('GET', 'POST')->name('setting');


/*
 * Run app
 */
$app->run();