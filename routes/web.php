<?php

use RECHARGE\controllers\AdminController;
use RECHARGE\controllers\AuthController;
use RECHARGE\controllers\MainController;
use RECHARGE\controllers\PaymentController;
use RECHARGE\controllers\ProfileController;

Flight::route('GET /', MainController::home(...));
Flight::route('/juego/mobile-legends', MainController::game(...));
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
