<?php

use SecucardConnect\Product\Common\Model\Address;
use SecucardConnect\Product\Common\Model\Contact;
use SecucardConnect\Product\Payment\Model\Customer;
use SecucardConnect\Product\Payment\Model\Container;
use SecucardConnect\Product\Payment\Model\SecupayDebit;
use SecucardConnect\Product\Payment\Model\SecupayPrepay;
use SecucardConnect\Product\Payment\Model\Transaction;
use SecucardConnect\Product\Payment\Model\Data;
use SecucardConnect\Product\Payment\Model\Contract;

// initialize the $secucard client
require_once __DIR__ . "/lib/init.php";

/*
 * Register helper for auth check
 */

class toolClass
{
    public function authCheck($app, $secucard)
    {
        try {
            $list = $secucard->payment->contracts->getList([]);
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

    $new_customer = null;
    $service = $secucard->payment->customers;

    /*
     * Creation of new customer
     */
    if ($app->request->isPost()) {

        $contact = new Contact();
        $contact->salutation = $app->request->post('salutation');
        $contact->title = $app->request->post('title');
        $contact->forename = $app->request->post('forename');
        $contact->surname = $app->request->post('surname');
        $contact->companyname = $app->request->post('companyname');
        $contact->dob = $app->request->post('dob');
        $contact->birthplace = $app->request->post('birthplace');
        $contact->nationality = 'DE';
        $contact->email = $app->request->post('email');
        $contact->phone = $app->request->post('phone');

        $address = new Address();
        $address->street = $app->request->post('street');
        $address->street_number = $app->request->post('street_number');
        $address->city = $app->request->post('city');
        $address->country = $app->request->post('country');
        $address->postal_code = $app->request->post('postal_code');

        $contact->address = $address;

        $new_customer = new Customer();
        $new_customer->contact = $contact;

        try {
            $new_customer = $service->save($new_customer);
        } catch (Exception $e) {
            $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
            $app->stop();
        }
        if (!$new_customer) {
            echo 'Failed to create customer';
        }
    }
    /**
     * set expiration time for the lists (5 minutes)
     */
    $expiration_time = '5m';

    /*
     * Load list
     */
    $items = [];
    $list = $service->getScrollableList([], $expiration_time);
    while (count($list) != 0) {
        $items = array_merge($items, $list->items);
        $list = $service->getNextBatch($list->scrollId);
    }

    // Render view
    $app->render('customer.twig', array('form' => $app->request->post(), 'new' => $new_customer, 'list' => $items));

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

    $new_container = null;
    $service = $secucard->payment->containers;

    /*
     * Creation of new container
     */
    if ($app->request->isPost()) {
        $container_data = new Data();
        $container_data->owner = $app->request->post('owner');
        $container_data->iban = $app->request->post('iban');

        $customer = new Customer();
        $customer->object = 'payment.customers';
        $customer->id = $app->request->post('customer_id');

        $new_container = new Container();
        $new_container->customer = $customer;
        $new_container->private = $container_data;

        // contract is for the container optional, but helpful for mandate generation
        $contract_id = $app->request->post('contract_id');
        if (!empty($contract_id)) {
            $contract = new Contract();
            $contract->id = $contract_id;
            $contract->object = 'payment.contracts';
            $new_container->contract = $contract;
        }

        try {
            $new_container = $service->save($new_container);
        } catch (Exception $e) {
            $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
            $app->stop();
        }
        if (!$new_container) {
            echo 'Failed to create container';
        }
    }
    /**
     * set expiration time for the lists (5 minutes)
     */
    $expiration_time = '5m';

    /*
     * Load list
     */
    $items = [];
    $list = $service->getScrollableList([], $expiration_time);
    while (count($list) != 0) {
        $items = array_merge($items, $list->items);
        $list = $service->getNextBatch($list->scrollId);
    }

    // Render view
    $app->render('container.twig', array('form' => $app->request->post(), 'new' => $new_container, 'list' => $items));

})->via('GET', 'POST')->name('container');


/*
 * Create Secupaydebit
 */
$app->map('/secupaydebit', function () use ($app, $secucard) {

    // Auth check
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    $new_debit = null;

    /*
     * Create new Secupaydebit
     */
    if ($app->request->isPost() && $app->request->post('amount')) {

        $container = new Container();
        $container->object = 'payment.containers';
        $container->id = $app->request->post('container_id');

        $customer = new Customer();
        $customer->object = 'payment.customers';
        $customer->id = $app->request->post('customer_id');

        $new_debit = new SecupayDebit();
        $new_debit->amount = $app->request->post('amount');
        $new_debit->currency = $app->request->post('currency');
        $new_debit->purpose = $app->request->post('purpose');
        $new_debit->order_id = $app->request->post('order_id');
        $new_debit->container = $container;
        $new_debit->customer = $customer;

        $contract_id = $app->request->post('contract_id');
        if (!empty($contract_id)) {
            $contract = new Contract();
            $contract->id = $contract_id;
            $contract->object = 'payment.contracts';
            $new_debit->contract = $contract;
        }

        $service = $secucard->payment->secupaydebits;

        try {
            $new_debit = $service->save($new_debit);
        } catch (\Exception $e) {
            $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
            $app->stop();
        }
        if (!$new_debit) {
            echo 'Failed to create secupaydebit payment';
        }
    }

    // Render view
    $app->render('secupaydebit.twig', array('form' => $app->request->post(), 'new' => $new_debit));

})->via('GET', 'POST')->name('secupaydebit');


/*
 * Show Secupaydebit details
 */
$app->get('/secupaydebit/:id', function ($id) use ($app, $secucard) {

    $e = null;
    if (empty($id)) {
        $e = new \Exception('empty parameter id');
    }

    // Auth check
    if ($e || ($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    $data = $secucard->payment->Secupaydebits->get($id);

    // Render view
    $app->render('secupaydebit_detail.twig', array('data' => $data));
});


/*
 * Create Secupayprepay
 */
$app->map('/secupayprepay', function () use ($app, $secucard) {

    // Auth check
    if (($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    $new_prepay = null;

    /*
     * Create new Secupayprepay
     */
    if ($app->request->isPost() && $app->request->post('amount')) {


        $customer = new Customer();
        $customer->object = 'payment.customers';
        $customer->id = $app->request->post('customer_id');

        $new_prepay = new SecupayPrepay();
        $new_prepay->amount = $app->request->post('amount');
        $new_prepay->currency = $app->request->post('currency');
        $new_prepay->purpose = $app->request->post('purpose');
        $new_prepay->order_id = $app->request->post('order_id');
        $new_prepay->customer = $customer;

        $contract_id = $app->request->post('contract_id');
        if (!empty($contract_id)) {
            $contract = new Contract();
            $contract->id = $contract_id;
            $contract->object = 'payment.contracts';
            $new_prepay->contract = $contract;
        }

        $service = $secucard->payment->secupayprepays;

        try {
            $new_prepay = $service->save($new_prepay);
        } catch (\Exception $e) {
            $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
            $app->stop();
        }
        if (!$new_prepay) {
            echo 'Failed to create secupayprepay payment';
        }
    }

    // Render view
    $app->render('secupayprepay.twig', array('form' => $app->request->post(), 'new' => $new_prepay));

})->via('GET', 'POST')->name('secupayprepay');


/*
 * Show Secupayprepay details
 */
$app->get('/secupayprepay/:id', function ($id) use ($app, $secucard) {

    $e = null;
    if (empty($id)) {
        $e = new \Exception('empty parameter id');
    }

    // Auth check
    if ($e || ($e = $app->tool->authCheck($app, $secucard)) !== true) {
        $app->render('exception.twig', array('exception' => $e, 'name' => get_class($e)));
        $app->stop();
    }

    $data = $secucard->payment->Secupayprepays->get($id);

    // Render view
    $app->render('secupayprepay_detail.twig', array('data' => $data));
});


/*
 * Auth settings
 */
$app->map('/settings', function () use ($app, $secucard, $config_sdk, $client_id, $client_secret) {

    $server_host = $config_sdk['base_url'];

    /*
     * Save?
     */
    if ($app->request->isPost()) {

        $credentials = array(
            'client_id' => $app->request->post('client_id'),
            'client_secret' => $app->request->post('client_secret'),
            // use trim to remove slashes from the beginning and end of server_host
            'server_host' => trim($app->request->post('server_host'), '/'),
        );

        // overwrite
        $client_id = $credentials['client_id'];
        $client_secret = $credentials['client_secret'];
        $server_host = $credentials['server_host'];

        // Save to cookie
        $app->setCookie('secucard-connect-demo', json_encode($credentials), '2 days');
    }

    // Render view
    $app->render('setting.twig', ['client_id' => $client_id, 'client_secret' => $client_secret, 'server_host' => $server_host]);

})->via('GET', 'POST')->name('settings');


/*
 * Run app
 */
$app->run();