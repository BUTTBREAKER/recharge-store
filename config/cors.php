<?php

declare(strict_types=1);

// API: CORS global para que las respuestas de error (404/401/403) también
// salgan con los headers CORS y el navegador pueda leerlas. La autenticación
// es middleware por grupo de rutas (ver routes/api.php).
Flight::before('start', static function (): void {
    $url = Flight::request()->url;

    if (!str_starts_with($url, '/api')) {
        return;
    }

    $origin = $_ENV['CORS_ORIGIN'] ?? 'http://localhost:3000';

    Flight::response()
        ->header('Access-Control-Allow-Origin', $origin)
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header(
            'Access-Control-Allow-Headers',
            'Authorization, Content-Type, X-Requested-With',
        )
        ->header(
            'Access-Control-Allow-Methods',
            'GET, POST, PUT, DELETE, OPTIONS',
        );

    if (Flight::request()->method === 'OPTIONS') {
        Flight::halt(204, '');
    }
});
