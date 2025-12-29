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

// Configurar la ruta de las vistas
Flight::set('flight.views.path', __DIR__ . '/../app/views');

// Cargar rutas
require __DIR__ . '/../app/routes/web.php';
require __DIR__ . '/../app/routes/admin.php';

Flight::start();
