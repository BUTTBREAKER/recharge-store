<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Middleware\ApiAuth;
use flight\net\Router;

// Rutas de la API JSON — consumida por el frontend Next.js.
//
// Patrón de grupos de Flight: Flight::group($prefijo, $callback, $middlewares).
// El CORS vive como hook global en public/index.php (para cubrir también los
// 404/401/403); la autenticación se adjunta como middleware de grupo, tal como
// la guía oficial de Flight: Flight::group(..., [ new AuthMiddleware() ]).
Flight::group('/api', static function (Router $router): void {
    // Health check: confirma que la API viva y expone el modo de auth
    $router->get('/health', static function (): void {
        Flight::json([
            'ok' => true,
            'service' => 'recharge-store-api',
            'auth_strict' => ApiAuth::strict(),
            'time' => date('c'),
        ]);
    });

    // Autenticación (credenciales, públicas)
    $router->post('/login', AuthController::login(...));
    $router->post('/register', AuthController::register(...));
    $router->post('/forgot-password', AuthController::forgotPassword(...));
    $router->post('/reset-password', AuthController::resetPassword(...));

    // Cierre de sesión: exige token en modo estricto
    Flight::group('/logout', static function (Router $router): void {
        $router->post('', AuthController::logout(...));
    }, [new ApiAuth()]);

    // Catálogo público
    Flight::group('/games', static function (Router $router): void {
        $router->get('', CatalogController::games(...));
        $router->get('/@slug', CatalogController::game(...));
        $router->get('/@slug/products', CatalogController::products(...));
    });

    // Config pública
    Flight::group('/config', static function (Router $router): void {
        $router->get('/exchange-rate', CatalogController::exchangeRate(...));
    });
});
