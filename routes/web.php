<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

Flight::route('GET /', MainController::home(...));
Flight::route('GET /juego/@slug:[a-z0-9\-]+', MainController::game(...));
Flight::route('POST /checkout', MainController::checkout(...));
Flight::route('POST /procesar-pago', MainController::procesarPago(...));

// Rutas de Pago (se implementarán en la siguiente fase)
Flight::route('/pago/pagomovil/@id', PaymentController::pagomovil(...));
Flight::route('POST /pago/pagomovil/confirmar', PaymentController::confirmarPagomovil(...));
Flight::route('/pago/binance/@id', PaymentController::binance(...));
Flight::route('/pago/estado/@id', PaymentController::estado(...));

// Auth Routes
Flight::route('GET /login', AuthController::loginView(...));
Flight::route('POST /login', AuthController::login(...));
Flight::route('GET /register', AuthController::registerView(...));
Flight::route('POST /register', AuthController::register(...));
Flight::route('/logout', AuthController::logout(...));

// User Profile Routes
Flight::route('/profile', ProfileController::userProfile(...));
Flight::route('POST /profile/update', ProfileController::updateProfile(...));
Flight::route('POST /profile/password', ProfileController::changePassword(...));
Flight::route('/profile/orders', ProfileController::orderHistory(...));

// Notifications
Flight::route('/notifications', MainController::notifications(...));

// Páginas estáticas
Flight::route('/legal', MainController::legal(...));
Flight::route('/reembolsos', MainController::reembolsos(...));

Flight::group('/admin', function () {
    // Dashboard & Analytics
    Flight::route('/dashboard', AdminController::dashboard(...));

    // Price Management
    Flight::route('/prices', AdminController::managePrices(...));
    Flight::route('POST /prices/update', AdminController::updatePrice(...));
    Flight::route('POST /prices/toggle', AdminController::toggleProduct(...));

    // Product CRUD
    Flight::route('/products/create', AdminController::productCreate(...));
    Flight::route('POST /products/store', AdminController::productStore(...));
    Flight::route('/products/edit/@id', AdminController::productEdit(...));
    Flight::route('POST /products/update', AdminController::productUpdate(...));
    Flight::route('POST /products/delete', AdminController::productDelete(...));

    // Games CRUD
    Flight::route('/games', AdminController::gamesIndex(...));
    Flight::route('/games/create', AdminController::gameCreate(...));
    Flight::route('POST /games/store', AdminController::gameStore(...));
    Flight::route('/games/edit/@id', AdminController::gameEdit(...));
    Flight::route('POST /games/update', AdminController::gameUpdate(...));
    Flight::route('POST /games/delete', AdminController::gameDelete(...));
    Flight::route('POST /games/toggle', AdminController::gameToggle(...));

    // User Management
    Flight::route('/users', AdminController::usersIndex(...));
    Flight::route('/users/edit/@id', AdminController::userEdit(...));
    Flight::route('POST /users/update', AdminController::userUpdate(...));
    Flight::route('POST /users/change-password', AdminController::userChangePassword(...));
    Flight::route('POST /users/make-admin', AdminController::userMakeAdmin(...));
    Flight::route('POST /users/make-user', AdminController::userMakeUser(...));
    Flight::route('POST /users/delete', AdminController::userDelete(...));

    // Payment Configuration
    Flight::route('/payments', AdminController::managePayments(...));
    Flight::route('POST /payments/update', AdminController::updatePaymentData(...));

    // Profile
    Flight::route('/profile', AdminController::profile(...));
    Flight::route('POST /profile/update', AdminController::updateProfile(...));
    Flight::route('POST /profile/password', AdminController::changePassword(...));

    // Order Management
    Flight::route('/orders', AdminController::ordersManagement(...));
    Flight::route('POST /orders/verify/@id', AdminController::verifyPayment(...));
    Flight::route('POST /orders/complete/@id', AdminController::completeOrder(...));
    Flight::route('POST /orders/reject/@id', AdminController::rejectPayment(...));

    // System Configuration
    Flight::route('/config', AdminController::systemConfig(...));
    Flight::route('POST /config/exchange-rate', AdminController::updateExchangeRate(...));

    // Order Management (existing)
    Flight::route('/pedido/@id', AdminController::verPedido(...));
    Flight::route('POST /pedido/actualizar', AdminController::actualizarEstado(...));
});
