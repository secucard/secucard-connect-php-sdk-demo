<?php

use SecucardConnect\Auth\RefreshTokenCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Smart\Model\BasketInfo;
use SecucardConnect\Product\Smart\Model\Transaction;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

require_once __DIR__ . "/../../shared/init-slim.php";

$app->view->getInstance()->getLoader()->addPath(__DIR__ . "/views");

/*
 * SDK config
 */
$sdkConfig = array();

/*
 * Prepare secucard connect SDK
 */

// the configuration for secucard client is stored in session or posted directly
// Get credentials from cookie if avail
$cookie = json_decode($app->getCookie('secucard-connect-demo'), true);
if (!empty($cookie['client_id'])) {
    $clientId = $cookie['client_id'];
    $clientSec = $cookie['client_secret'];
}

/*
 *
 */
$id = $app->request->post('client_id');
if (!empty($id)) {
    $clientId = $id;
    $clientSec = $app->request->post('client_secret');
}

// get correct base_url
$serverHost = $app->request->post('server_host');
if (empty($serverHost)) {
    $serverHost = $cookie['server_host'];
}

$baseUrl = $serverHost;
if (!empty($baseUrl)) {
    if (strpos($baseUrl, 'https://') !== false) {
        $sdkConfig['base_url'] = $baseUrl;
    } else {
        $sdkConfig['base_url'] = "https://" . $baseUrl;
    }
}
$sdkConfig['server_host'] = $serverHost;

$refreshToken = $app->request->get('refresh_token');
if (empty($refreshToken)) {
    $refreshToken = $app->request->post('refresh_token');
}
if (empty($refreshToken)) {
    $refreshToken = $cookie['refresh_token'];
}

// We use refresh credentials here to avoid the device auth flow in this demo.
// In production this must be an instance of DeviceCredentials.
$cred = new RefreshTokenCredentials($clientId, $clientSec, $refreshToken);

// This just the internal logger impl. for demo purposes! For production you may use a library like Monolog.
$logger = new Logger(null, true);

// Use DummyStorage for demo purposes only, in production use FileStorage or your own implementation.
$store = new DummyStorage();

// create client
$secucard = new SecucardConnect($sdkConfig, $logger, $store, $store, $cred);

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

        $credentials = $app->getCookie('secucard-connect-demo');
        $credentials = json_decode($credentials, true);

        $auth_data = [
            'vendor' => $app->request->post('vendor'),
            'uid' => $app->request->post('uid'),
        ];

        $poll_error = '';
        if (isset($_POST['startBtn'])) {
            if (empty($auth_data['vendor']) || empty($auth_data['uid'])) {
                $polling_data['request_error'] = 'Error: empty vendor or uid';
            } else {
                $polling_data = $secucard->authenticate("vendor/" . $auth_data['vendor'], $auth_data['uid']);
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

            $token = ""; // $secucard->pollDeviceAccessToken($polling_data['device_code']);

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
$app->map('/transaction', function () use ($app, $secucard, $refreshToken, $serverHost, $logger) {

    // check if refresh_token is defined, if not then error:
    if (empty($refreshToken)) {
        $error = new \Exception('Empty refresh_token');
        $app->render('exception.twig', array('exception' => $error, 'name' => 'Missing refresh_token token'));
        $app->stop();
    }

    $amount = $_GET['amount'];
    $merchantref = $_GET['merchant_ref'];
    $transref = $_GET['trans_ref'];

    $service = null;
    $error = '';

    $trans = null;

    if ($amount) {
        // creation:
        $trans = new Transaction();
        $trans->merchantRef = $merchantref;
        $trans->transactionRef = $transref;
        $trans->basket_info = new BasketInfo((int)$amount, 'EUR');

        $service = $secucard->smart->transactions;

        try {
            $trans = $service->save($trans);
        } catch (Exception $e) {
            $error = 'Error : ' . $e->getMessage();
        }
    }


    // Render view
    $token = $secucard->accessTokenForJS();
    $app->render('transaction.twig', ['transaction' => $trans, 'token' => $token, 'error' => $error,
        'amount' => $amount, 'merchant_ref' => $merchantref, 'trans_ref' => $transref, 'host' => $serverHost]);

})->via('GET')->name('transaction');


/*
 * Settings
 */
$app->map('/settings', function () use ($app, $clientId, $clientSec, $refreshToken, $serverHost) {

    /*
     * Save settings.
     */
    if ($app->request->isPost()) {

        $cookie = array(
            'client_id' => $app->request->post('client_id'),
            'client_secret' => $app->request->post('client_secret'),
            'server_host' => $app->request->post('server_host'),
            'refresh_token' => $app->request->post('refresh_token'),
        );

        // overwrite
        $clientId = $cookie['client_id'];
        $clientSec = $cookie['client_secret'];
        $serverHost = $cookie['server_host'];
        $refreshToken = $cookie['refresh_token'];

        // Save to cookie
        $app->setCookie('secucard-connect-demo', json_encode($cookie), '2 days');
    }

    // Render view
    $app->render('settings.twig', array('client_id' => $clientId, 'client_secret' => $clientSec, 'server_host' => $serverHost, 'refresh_token' => $refreshToken));

})->via('GET', 'POST')->name('settings');


/*
 * Run app
 */
$app->run();