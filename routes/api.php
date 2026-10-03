<?php

use App\Http\Controllers\Api\Admin\CatalogController as AdminCatalogController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
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
    Flight::group(
        '/logout',
        static function (Router $router): void {
            $router->post('', AuthController::logout(...));
        },
        [new ApiAuth()],
    );

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

    // Pedidos y pagos: identidad opcional (checkout como invitado, o con
    // Bearer para asociar el pedido al usuario logueado).
    Flight::group(
        '/orders',
        static function (Router $router): void {
            $router->post('', OrderController::create(...));
            $router->post('/@id/pay', OrderController::pay(...));
            $router->get('/@id/status', OrderController::status(...));
            $router->get('/@id/binance', OrderController::binance(...));
        },
        [new ApiAuth(enforce: false)],
    );

    Flight::group(
        '/payments',
        static function (Router $router): void {
            $router->post(
                '/pagomovil',
                PaymentController::confirmPagomovil(...),
            );
        },
        [new ApiAuth(enforce: false)],
    );

    // Perfil: identidad requerida (funcional en ambos modos)
    Flight::group(
        '/profile',
        static function (Router $router): void {
            $router->get('', ProfileController::show(...));
            $router->put('', ProfileController::update(...));
            $router->put('/password', ProfileController::updatePassword(...));
            $router->get('/orders', ProfileController::orders(...));
        },
        [new ApiAuth()],
    );

    // Notificaciones: identidad requerida
    Flight::group(
        '/notifications',
        static function (Router $router): void {
            $router->get('', NotificationController::index(...));
            $router->post('/read-all', NotificationController::readAll(...));
        },
        [new ApiAuth()],
    );

    // Panel admin: exige rol admin en modo estricto (401 sin token, 403 sin rol)
    Flight::group(
        '/admin',
        static function (Router $router): void {
            // Dashboard y configuración del sistema
            $router->get('/dashboard', AdminDashboardController::show(...));
            $router->get(
                '/payment-config',
                AdminSettingsController::paymentConfig(...),
            );
            $router->put(
                '/payment-config',
                AdminSettingsController::updatePaymentConfig(...),
            );
            $router->put(
                '/exchange-rate',
                AdminSettingsController::updateExchangeRate(...),
            );

            // Gestión de pedidos/recargas
            $router->get('/orders', AdminOrderController::index(...));
            $router->get('/orders/@id', AdminOrderController::show(...));
            $router->post(
                '/orders/@id/verify',
                AdminOrderController::verify(...),
            );
            $router->post(
                '/orders/@id/complete',
                AdminOrderController::complete(...),
            );
            $router->post(
                '/orders/@id/reject',
                AdminOrderController::reject(...),
            );
            $router->put(
                '/orders/@id/estado',
                AdminOrderController::updateEstado(...),
            );

            // CRUD de productos (incluye precios)
            $router->get('/products', AdminCatalogController::products(...));
            $router->post(
                '/products',
                AdminCatalogController::productStore(...),
            );
            $router->put(
                '/products/@id',
                AdminCatalogController::productUpdate(...),
            );
            $router->delete(
                '/products/@id',
                AdminCatalogController::productDelete(...),
            );
            $router->put(
                '/products/@id/price',
                AdminCatalogController::productUpdatePrice(...),
            );
            $router->post(
                '/products/@id/toggle',
                AdminCatalogController::productToggle(...),
            );

            // CRUD de juegos
            $router->get('/games', AdminCatalogController::games(...));
            $router->post('/games', AdminCatalogController::gameStore(...));
            $router->put('/games/@id', AdminCatalogController::gameUpdate(...));
            $router->delete(
                '/games/@id',
                AdminCatalogController::gameDelete(...),
            );
            $router->post(
                '/games/@id/toggle',
                AdminCatalogController::gameToggle(...),
            );

            // Gestión de usuarios
            $router->get('/users', AdminUserController::index(...));
            $router->get('/users/@id', AdminUserController::show(...));
            $router->put(
                '/users/@id/role',
                AdminUserController::updateRole(...),
            );
            $router->delete('/users/@id', AdminUserController::destroy(...));
        },
        [new ApiAuth(requireAdmin: true)],
    );
});
