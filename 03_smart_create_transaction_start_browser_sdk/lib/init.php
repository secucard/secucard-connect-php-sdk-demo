<?php

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", 1);

error_reporting(E_ALL && ~E_NOTICE);
#error_reporting(E_ALL);

require_once __DIR__ . "/../../vendor/autoload.php";

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

$config_app = array(
    'debug' => true,
    'templates.path' => __DIR__ . "/../views"
);

/*
 * Prepare demo app
 */
$app = new \Slim\Slim($config_app);

$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('app');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Prepare twig-view-renderer
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
    'debug' => true,
);

// add shared template path to loader
$app->view->getInstance()->getLoader()->addPath(__DIR__ . "/../../shared/templates");
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// add extensions
$app->view->getInstance()->addExtension(new Twig_Extension_Debug());

// add methods
$function = new Twig_SimpleFunction('urlFor', function ($name) use ($app) {
    return $app->urlFor($name);
});

$app->view->getInstance()->addFunction($function);


/*
 * Prepare secucard connect SDK
 */

$logger = new \Monolog\Logger('sdk');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/sdk.log', \Monolog\Logger::DEBUG));

// the configuration for secucard client is stored in session or posted directly
// Get credentials from cookie if avail
$credentials = $app->getCookie('secucard-connect-demo');
$credentials = (array)json_decode($credentials);
if (!empty($credentials['client_id'])) {
    $config_sdk['client_id'] = $credentials['client_id'];
    $config_sdk['client_secret'] = $credentials['client_secret'];
}

if (!empty($_POST['client_id'])) {
    $config_sdk['client_id'] = $_POST['client_id'];
    $config_sdk['client_secret'] = $_POST['client_secret'];
}

// get correct base_url
$base_url = empty($_POST['server_host']) ? $credentials['server_host'] : $_POST['server_host'];
if (!empty($base_url)) {
    if (strpos($base_url, 'https://') !== false) {
        $config_sdk['base_url'] = $base_url;
    } else {
        $config_sdk['base_url'] = "https://" . $base_url;
    }
}

$refresh_token = empty($_POST['refresh_token']) ? $credentials['refresh_token'] : $_POST['refresh_token'];
$config_sdk['refresh_token'] = $refresh_token;


// Dummy Log File
$fp = fopen("/tmp/secucard_demo.log", "a");
$logger = new \secucard\client\log\Logger($fp, true);

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