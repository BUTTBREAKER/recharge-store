<?php

declare(strict_types=1);

use flight\Container;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

///////////////
// CONSTANTS //
///////////////
const ROOT_FOLDER_PATH = __DIR__ . '/..';

require_once ROOT_FOLDER_PATH . '/vendor/autoload.php';

///////////////////////////
// ENVIRONMENT VARIABLES //
///////////////////////////
new Dotenv()->load(
    ROOT_FOLDER_PATH . '/.env.example',
    ROOT_FOLDER_PATH . '/.env',
);

$configsPaths = glob(ROOT_FOLDER_PATH . '/config/*.php');

foreach ($configsPaths ? $configsPaths : [] as $configs) {
    require_once $configs;
}

/////////////////////////////////////
// DEPENDENCIES INJECTOR CONTAINER //
/////////////////////////////////////
Container::getInstance()->singleton(
    SluggerInterface::class,
    AsciiSlugger::class,
);

//////////////////////////////
// FLIGHTPHP CONFIGURATIONS //
//////////////////////////////
Flight::registerContainerHandler(Container::getInstance());

Flight::set('flight.base_url', str_replace(
    '/index.php',
    replace: '',
    subject: $_SERVER['SCRIPT_NAME'],
));

Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', false);
Flight::set('flight.log_errors', false);
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);

// LOAD ROUTES
$routesPaths = glob(ROOT_FOLDER_PATH . '/routes/*.php');

foreach ($routesPaths ? $routesPaths : [] as $routes) {
    require_once $routes;
}

Flight::start();
