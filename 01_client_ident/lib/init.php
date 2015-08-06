<?php

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", 1);
#error_reporting(E_ALL);

require_once __DIR__ . "/../../vendor/autoload.php";

/*
 * CONFIG
 */

$config_sdk = array(
    'client_id'=>'XXXX',
    'client_secret'=>'XXXXXXX',
    'debug' => true,
);

$config_app = array(
    'debug' => true,
    'templates.path' => __DIR__."/../views" #'../../shared/templates'
);

// OVERWRITE DEFAULTS FROM CONFIG
include "config.php";

/*
 * Prepare common
 */

$logger = new \Monolog\Logger('sdk');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../logs/sdk.log', \Monolog\Logger::DEBUG));

/*
 * Prepare secucard connect SDK
 */

$secucard = new secucard\Client($config_sdk, $logger);

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