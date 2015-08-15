<?php

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", 1);
#error_reporting(E_ALL);

require_once __DIR__ . "/../../vendor/autoload.php";

/*
 * CONFIG
 */

$config_sdk = array(
    'client_id'=> 'overwrite with cookie when set',
    'client_secret' => 'overwrite with cookie when set',
    'debug' => true,
);

$config_app = array(
    'debug' => true,
    'templates.path' => __DIR__."/../views" #'../../shared/templates'
);

/*
 * Prepare demo app
 */

$app = new \Slim\Slim($config_app);

$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('app');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../logs/app.log', \Monolog\Logger::DEBUG));
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
$app->view->getInstance()->getLoader()->addPath(__DIR__."/../../shared/templates");
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
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../logs/sdk.log', \Monolog\Logger::DEBUG));

// Get credentials from cookie if avail
$credentials = $app->getCookie('secucard-connect-demo');

if ($credentials) {

    $credentials = json_decode($credentials);

    if (isset($credentials->client_id) && !empty($credentials->client_id)) {
        $config_sdk['client_id'] = $credentials->client_id;
        $config_sdk['client_secret'] = $credentials->client_secret;
    }

    if (isset($credentials->server_host) && !empty($credentials->server_host)) {
        if (strpos($credentials->server_host, 'https://') !== false) {
            $config_sdk['base_url'] = $credentials->server_host;
        } else {
            $config_sdk['base_url'] = "https://" . $credentials->server_host;
        }
    }
}

$secucard = new secucard\Client($config_sdk, $logger);