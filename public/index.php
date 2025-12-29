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

// Autoload manual para clases MVC (o usar composer psr-4)
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/middlewares/'
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Cargar rutas
require __DIR__ . '/../app/routes/web.php';
require __DIR__ . '/../app/routes/admin.php';

Flight::start();
