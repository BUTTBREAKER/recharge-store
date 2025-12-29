<?php
require __DIR__ . '/../vendor/autoload.php';

// Cargar configuración
$config = require __DIR__ . '/../config/config.php';

// Registrar base de datos en Flight
Flight::register('db', 'PDO', [
    "mysql:host={$config['database']['host']};dbname={$config['database']['name']};charset=utf8mb4",
    $config['database']['user'],
    $config['database']['pass']
], function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
});

// Configurar la ruta de las vistas
Flight::set('flight.views.path', __DIR__ . '/../app/views');

// Cargar rutas
require __DIR__ . '/../app/routes/web.php';
require __DIR__ . '/../app/routes/admin.php';

Flight::start();
