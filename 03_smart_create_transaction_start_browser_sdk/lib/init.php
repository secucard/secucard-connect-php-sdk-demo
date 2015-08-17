<?php

// variable used later
define('TWIG_VIEWS_PATH', __DIR__ . "/../views");

include_once __DIR__ . "/../../shared/php/init.php";

/*
 * CONFIG
 */

$config_sdk = array(
    'client_id' => 'overwrite with cookie when set',
    'client_secret' => 'overwrite with cookie when set',
    'debug' => true,
    // authorisation type none required
    'auth' => array('type' => 'none'),
);

/*
 * Prepare secucard connect SDK
 */

$logger = new \Monolog\Logger('sdk');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/sdk.log', \Monolog\Logger::DEBUG));

// the configuration for secucard client is stored in session or posted directly
// Get credentials from cookie if avail
$credentials = $app->getCookie('secucard-connect-demo');
$credentials = json_decode($credentials, true);
if (!empty($credentials['client_id'])) {
    $config_sdk['client_id'] = $credentials['client_id'];
    $config_sdk['client_secret'] = $credentials['client_secret'];
}
/*
 *
 */
$client_id = $app->request->post('client_id');
if (!empty($client_id)) {
    $config_sdk['client_id'] = $client_id;
    $config_sdk['client_secret'] = $app->request->post('client_secret');
}

// get correct base_url
$server_host = $app->request->post('server_host');
$base_url = empty($server_host) ? $credentials['server_host'] : $server_host;
if (!empty($base_url)) {
    if (strpos($base_url, 'https://') !== false) {
        $config_sdk['base_url'] = $base_url;
    } else {
        $config_sdk['base_url'] = "https://" . $base_url;
    }
}

$refresh_token = $app->request->post('refresh_token');
if (empty($refresh_token)) {
    $credentials['refresh_token'];
}
$config_sdk['refresh_token'] = $refresh_token;


// Dummy storage for client
$storage = new \secucard\client\storage\DummyStorage();

// initialize storage from session
if (!empty($refresh_token)) {
    $storage->set('refresh_token', $refresh_token);
    // set authorization type for client
    $config_sdk['auth'] = ['type' => 'refresh_token', 'refresh_token' => $refresh_token];
}

// create client
$secucard = new \secucard\Client($config_sdk, $logger, $storage);