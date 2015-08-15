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
            return $e;
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
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
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

        /*
        $identrequestPerson = $secucard->factory('Services\IdentrequestsPerson');

        $identrequestPerson->contact = $contact_data;
        $identrequestPerson->custom1 = 'custom_test';

        $identrequest = new secucard\models\Services\Identrequests($secucard);
        $identrequest->owner_transaction_id = 'tx_32874283492098479';
        $identrequest->type = 'person';
        $identrequest->demo = true;

        $identrequest->addRelated('person', $identrequestPerson);

        $new = $identrequest->save();
        */

        $new = "test";
    }

    /*
     * Load list
     */
    $list = $secucard->services->identresults->getList(array());

    // Render view
    $app->render('request.twig', array('form' => $app->request->post(), 'new' => $new, 'list' => $list));

})->via('GET', 'POST')->name('request');


/*
 * Show Identrequest details
 */
$app->get('/request(/:id)', function ($id = false) use ($app, $secucard, $config_sdk) {

    $data = "details";

    // Render view
    $app->render('request_details.twig', array('data' => $data));

});


/*
 * Show Identresult
 */
$app->map('/result(/:id)', function ($id = false) use ($app, $secucard) {

    // Auth check
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    // Render view
    $app->render('result.twig');

})->via('GET', 'POST')->name('result');


/*
 * Auth settings
 */
$app->map('/setting', function () use ($app, $secucard, $config_sdk) {

    $client_id = $config_sdk['client_id'];
    $client_secret = $config_sdk['client_secret'];
    $server_host = $config_sdk['base_url'];

    /*
     * Save?
     */
    if ($app->request->isPost()) {

        $credentials = array(
            'client_id' => $app->request->post('client_id'),
            'client_secret' => $app->request->post('client_secret'),
            'server_host' => $app->request->post('server_host')
        );

        // overwrite
        $client_id = $credentials['client_id'];
        $client_secret = $credentials['client_secret'];
        $server_host = $credentials['server_host'];

        // Save to cookie
        $app->setCookie('secucard-connect-demo', json_encode($credentials), '2 days');
    }

    // Render view
    $app->render('setting.twig', array('client_id' => $client_id, 'client_secret' => $client_secret, 'server_host' => $server_host));

})->via('GET', 'POST')->name('setting');


/*
 * Run app
 */
$app->run();