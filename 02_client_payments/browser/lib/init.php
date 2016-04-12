<?php

// variable used later
define('TWIG_VIEWS_PATH', __DIR__ . "/../views");
define('APP_LOG_PATH', __DIR__ . '/../logs/app.log');

use SecucardConnect\Auth\RefreshTokenCredentials;
use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Smart\Model\BasketInfo;
use SecucardConnect\Product\Smart\Model\Transaction;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

require_once __DIR__ . "/../../../shared/init-slim.php";


$app->view->getInstance()->getLoader()->addPath(TWIG_VIEWS_PATH);

/*
 * Prepare secucard connect SDK
 */
$config_sdk = [];

// the configuration for secucard client is stored in session or posted directly
// Get credentials from cookie if avail
$cookie = json_decode($app->getCookie('secucard-connect-demo'), true);
if (!empty($cookie['client_id'])) {
    $client_id = $cookie['client_id'];
    $client_secret = $cookie['client_secret'];
}
$post_client_id = $app->request->post('client_id');
if (!empty($post_client_id)) {
    $client_id = $post_client_id;
    $client_secret = $app->request->post('client_secret');
}

// get correct base_url
$server_host = $app->request->post('server_host');
if (empty($server_host)) {
    $server_host = $cookie['server_host'];
}

$base_url = $server_host;
if (!empty($base_url)) {
    if (strpos($base_url, 'https://') !== false) {
        $config_sdk['base_url'] = $base_url;
    } else {
        $config_sdk['base_url'] = "https://" . $base_url;
    }
}
$config_sdk['server_host'] = $server_host;
$config_sdk['debug'] = true;

$refresh_token = $app->request->get('refresh_token');
if (empty($refresh_token)) {
    $refresh_token = $app->request->post('refresh_token');
}
if (empty($refresh_token)) {
    $refresh_token = $cookie['refresh_token'];
}

// if refresh token is empty, then for authorization we will use client credentials
if (empty($refresh_token)) {
    $cred = new ClientCredentials($client_id, $client_secret);
} else {
    $cred = new RefreshTokenCredentials($client_id, $client_secret, $refresh_token);
}

$logger = new Logger(fopen(APP_LOG_PATH, "a"), true);

$store = new DummyStorage();

// create client
$secucard = new SecucardConnect($config_sdk, $logger, $store, $store, $cred);

// TODO here we should store the refresh_token or access_token in the session, so we don't relog on every request
