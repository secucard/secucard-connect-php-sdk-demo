<?php

require_once __DIR__ . "/lib/init.php";


/*
 * Register helper for auth check
 */

class toolClass
{
    public function authCheck($app, $secucard)
    {
        try {
            $list = $secucard->payment->contracts->getList(array());
        } catch (Exception $e) {
            return $e;
        }

        return true;
    }

}

$app->tool = new toolClass();

/*
 * Main Page
 */
$app->map('/', function () use ($app, $secucard) {

    $exception = null;
    // Auth check
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $exception = $e;
    }

    // Render view
    $app->render('index.twig', ['exception' => $exception]);

})->via('GET')->name('index');


/*
 * Create Customer
 */
$app->map('/customer', function () use ($app, $secucard) {

    // Auth check
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    $new = null;

    /*
     * Save
     */
    if ($app->request->isPost()) {

        $contact_data = array(
            'salutation' => $app->request->post('salutation'),
            'title' => $app->request->post('title'),
            'forename' => $app->request->post('forename'),
            'surname' => $app->request->post('surname'),
            'companyname' => $app->request->post('companyname'),
            'dob' => $app->request->post('dob'),
            'birthplace' => $app->request->post('birthplace'),
            'nationality' => "de",
            'email' => $app->request->post('email'),
            'phone' => $app->request->post('phone'),
            'address' => array(
                'street' => $app->request->post('street'),
                'street_number' => $app->request->post('street_number'),
                'city' => $app->request->post('city'),
                'country' => $app->request->post('country'),
                'postal_code' => $app->request->post('postal_code'),
            )
        );

        $new_customer = $secucard->factory('Payment\Customers');

        $new_customer->contact = $contact_data;

        try {
            $success = $new_customer->save();
        } catch (\Exception $e) {
            $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
            $app->stop();
        }
        if (!$success) {
            echo 'Failed to create customer';
        }
    }

    /*
     * Load list
     */
    $list = $secucard->payment->customers->getList(array());

    // Render view
    $app->render('customer.twig', array('form' => $app->request->post(), 'new' => $new_customer, 'list' => $list));

})->via('GET', 'POST')->name('customer');


/*
 * Create Container
 */
$app->map('/container', function () use ($app, $secucard) {

    // Auth check
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    $new = null;

    /*
     * Save
     */
    if ($app->request->isPost()) {

        $container_data = [
            'type' => 'bank_account',
            'private' => [
                'owner' => $app->request->post('owner'),
                'iban' => $app->request->post('iban'),
            ]
        ];

        $new_container = $secucard->factory('Payment\Containers');

        $new_container->initValues($container_data);

        try {
            $success = $new_container->save();
        } catch (\Exception $e) {
            $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
            $app->stop();
        }
        if (!$success) {
            echo 'Failed to create container';
        }
    }

    /*
     * Load list
     */
    $list = $secucard->payment->containers->getList(array());

    // Render view
    $app->render('container.twig', array('form' => $app->request->post(), 'new' => $new_container, 'list' => $list));

})->via('GET', 'POST')->name('container');


/*
 * Show Identrequest details
 *
$app->get('/request(/:id)', function ($id = false) use ($app, $secucard, $config_sdk) {

    $data = "details";

    // Render view
    $app->render('request_details.twig', array('data' => $data));

});


/*
 * Show Identresult
 *
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
$app->map('/settings', function () use ($app, $secucard, $config_sdk) {

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

})->via('GET', 'POST')->name('settings');


/*
 * Run app
 */
$app->run();