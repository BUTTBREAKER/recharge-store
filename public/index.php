<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv)->load(__DIR__ . '/../.env.example', __DIR__ . '/../.env');

// Registrar base de datos en Flight
Flight::register('db', PDO::class, [
    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
], function (PDO $db): void {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
});

// Configurar la ruta de las vistas
Flight::set('flight.views.path', __DIR__ . '/../app/views');

// Cargar rutas
require __DIR__ . '/../app/routes/web.php';
require __DIR__ . '/../app/routes/admin.php';

Flight::start();
