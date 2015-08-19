<?php

date_default_timezone_set('Europe/Berlin');
ini_set("display_errors", 1);

error_reporting(E_ALL && ~E_NOTICE);

require_once __DIR__ . "/../../vendor/autoload.php";

/*
 * CONFIG
 */

$config_app = array(
    'debug' => true,
    'templates.path' => TWIG_VIEWS_PATH,
);

/*
 * Prepare demo app
 */

$app = new \Slim\Slim($config_app);

$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('app');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(APP_LOG_PATH, \Monolog\Logger::DEBUG));
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
$app->view->getInstance()->getLoader()->addPath(__DIR__ . "/../templates");
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// add extensions
$app->view->getInstance()->addExtension(new Twig_Extension_Debug());

// add methods
$function = new Twig_SimpleFunction('urlFor', function ($name) use ($app) {
    $url = $app->urlFor($name);
    // add index.php to url, so it is not broken
    if (strpos($url, 'index.php') === false) {
        $url = str_replace($name, 'index.php/' . $name, $url);
    }
    return $url;
});

$app->view->getInstance()->addFunction($function);
