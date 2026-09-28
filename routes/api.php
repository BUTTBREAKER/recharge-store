<?php

use App\Http\Middleware\ApiAuth;

// Rutas de la API JSON — consumida por el frontend Next.js.
// Agrupadas bajo /api. El auth (Bearer) y el CORS los manejan los
// middlewares registrados en public/index.php, no por ruta.
Flight::group('/api', static function (): void {
    // Health check: sirve para verificar que la API viva y saber el modo auth
    Flight::route('GET /health', static function (): void {
        Flight::json([
            'ok' => true,
            'service' => 'recharge-store-api',
            'auth_strict' => ApiAuth::strict(),
            'time' => date('c'),
        ]);
    });
});
