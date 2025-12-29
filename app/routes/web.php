<?php

use RECHARGE\controllers\AuthController;
use RECHARGE\controllers\MainController;
use RECHARGE\controllers\PaymentController;
use RECHARGE\controllers\ProfileController;

Flight::route('/', MainController::home(...));
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
