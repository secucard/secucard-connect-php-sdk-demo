<?php

use SecucardConnect\Util\Logger;
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

include_once __DIR__ . "/init.php";

/*
 * Prepare demo app
 */

$app = new Slim(array(
    'debug' => true,
    'templates.path' => __DIR__,
));


$app->container->singleton('log', function () {
    return new Logger(fopen("php://stdout", "a"), true);
});

// Prepare twig-view-renderer
$app->view(new Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
    'debug' => true,
);

$app->view->parserExtensions = array(new TwigExtension());

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