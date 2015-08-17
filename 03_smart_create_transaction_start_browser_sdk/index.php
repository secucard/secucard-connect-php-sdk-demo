<?php

require_once __DIR__ . "/lib/init.php";


/*
 * Device authorization page
 */
$app->map('/', function () use ($app, $secucard, $config_sdk) {

    // Get data from cookies
    $auth_data = $app->getCookie('secucard-connect-demo-3-auth');
    $auth_data = (array)json_decode($auth_data);

    $polling_data = $app->getCookie('secucard-connect-demo-3-polling');
    $polling_data = (array)json_decode($polling_data);

    /*
     * Save authorization
     */
    if ($app->request->isPost()) {

        $credentials = array(
            'client_id' => $app->request->post('client_id'),
            'client_secret' => $app->request->post('client_secret'),
            'server_host' => $app->request->post('server_host'),
            'refresh_token' => $app->request->post('refresh_token'),
        );

        $auth_data = [
            'vendor' => $app->request->post('vendor'),
            'uid' => $app->request->post('uid'),
        ];

        $poll_error = '';
        if (isset($_POST['startBtn'])) {
            if (empty($auth_data['vendor']) || empty($auth_data['uid'])) {
                $polling_data['request_error'] = 'Error: empty vendor or uid';
            } else {
                $polling_data = $secucard->obtainDeviceVerification("vendor/" . $auth_data['vendor'], $auth_data['uid']);
                $poll_error = $polling_data['error_description'];
            }
        } else {
            $polling_data = [
                'user_code' => $app->request->post('user_code'),
                'device_code' => $app->request->post('device_code'),
                'interval' => $app->request->post('interval'),
                'verification_url' => $app->request->post('verification_url'),
            ];
        }
        if (isset($_POST['pollBtn'])) {

            $token = $secucard->pollDeviceAccessToken($polling_data['device_code']);

            if (!empty($token['refresh_token'])) {
                $polling_data['successful'] = $token['refresh_token'];
                $credentials['refresh_token'] = $token['refresh_token'];
                $polling_data['token'] = $token;
            } elseif ($token['error'] !== 'authorization_pending') {
                $poll_error = 'Getting refresh token failed, error: ' . $token['error_description'];
            } else {
                $poll_error = "Pending, please retry Polling";
            }
        }

        $polling_data['error'] = $poll_error;

        // save cookies to session
        $app->setCookie('secucard-connect-demo', json_encode($credentials), '2 days');
        $app->setCookie('secucard-connect-demo-3-auth', json_encode($auth_data), '2 days');
        $app->setCookie('secucard-connect-demo-3-polling', json_encode($polling_data), '2 days');

        // display polling when user clicks the startBtn or pollBtn
        $polling_data['display_polling'] = empty($polling_data['request_error']) && (isset($_POST['startBtn']) || isset($_POST['pollBtn']));
    }

    // Render view
    $app->render('authorisation.twig', array('config_sdk' => $config_sdk, 'auth_data' => $auth_data, 'polling' => $polling_data));

// the name() call gives the name for current route and you can use it un urlFor() function
})->via('GET', 'POST')->name('authorisation');


/*
 * Create and start Transaction
 */
$app->map('/transaction', function () use ($app, $secucard, $credentials) {

    $amount = $_GET['amount'];
    $merchant_ref = $_GET['merchant_ref'];
    $trans_ref = $_GET['trans_ref'];

    $transaction = null;
    $error = '';

    if ($amount) {
        // creation:
        $transaction_data = array(
            'merchantRef' => $merchant_ref,
            'transactionRef' => $trans_ref,
            'basket_info' => [
                'sum' => (int)$amount,
                'currency' => 'EUR'
            ],
        );

        $transaction = $secucard->factory('Smart\Transactions');
        $transaction->initValues($transaction_data);

        $success = false;
        try {
            $success = $transaction->save();
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $error = '<b>Error message: </b> ' . $e->getMessage() . '<br/>';
            if ($e->hasResponse()) {
                if ($e->getResponse()->getBody()) {
                    $error .= '<b>Body: </b>' . print_r($e->getResponse()->getBody()->__toString(), true) . '<br/>';
                }
            }
        } catch (Exception $e) {
            $error = 'Error : ' . $e->getMessage();
        }
    }

    // set host to javascript sdk
    $host = (($credentials['server_host'] != "") ? $credentials['server_host'] : 'null');

    // Render view
    $app->render('transaction.twig', ['transaction' => $transaction, 'token' => $secucard->storage->get('access_token'),
        'error' => $error, 'amount' => $amount, 'merchant_ref' => $merchant_ref, 'trans_ref' => $trans_ref, 'host' => $host]);

})->via('GET')->name('transaction');


/*
 * Run app
 */
$app->run();