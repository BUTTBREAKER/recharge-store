<?php

use flight\Container;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv)->load(__DIR__ . '/../.env.example', __DIR__ . '/../.env');

Container::getInstance()->singleton(PDO::class, static function (): PDO {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
});

Flight::registerContainerHandler(Container::getInstance());

Flight::set('flight.base_url', null);
Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', false);
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->extension = '.php';
Flight::view()->path = __DIR__ . '/../resources/views';
Flight::view()->preserveVars = false;

// Cargar rutas
foreach (glob(__DIR__ . '/../routes/*.php') as $routes) {
    require_once $routes;
}

Flight::start();
