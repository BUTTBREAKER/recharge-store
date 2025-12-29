<?php
Flight::group('/admin', function() {
    // Dashboard & Analytics
    Flight::route('/dashboard', ['AdminController', 'dashboard']);
    
    // Price Management
    Flight::route('/prices', ['AdminController', 'managePrices']);
    Flight::route('POST /prices/update', ['AdminController', 'updatePrice']);
    Flight::route('POST /prices/toggle', ['AdminController', 'toggleProduct']);
    
    // Payment Configuration
    Flight::route('/payments', ['AdminController', 'managePayments']);
    Flight::route('POST /payments/update', ['AdminController', 'updatePaymentData']);
    
    // Profile
    Flight::route('/profile', ['AdminController', 'profile']);
    Flight::route('POST /profile/update', ['AdminController', 'updateProfile']);
    Flight::route('POST /profile/password', ['AdminController', 'changePassword']);
    
    // Order Management
    Flight::route('/orders', ['AdminController', 'ordersManagement']);
    Flight::route('POST /orders/verify/@id', ['AdminController', 'verifyPayment']);
    Flight::route('POST /orders/complete/@id', ['AdminController', 'completeOrder']);
    Flight::route('POST /orders/reject/@id', ['AdminController', 'rejectPayment']);
    
    // System Configuration
    Flight::route('/config', ['AdminController', 'systemConfig']);
    Flight::route('POST /config/exchange-rate', ['AdminController', 'updateExchangeRate']);
    
    // Order Management (existing)
    Flight::route('/pedido/@id', ['AdminController', 'verPedido']);
    Flight::route('POST /pedido/actualizar', ['AdminController', 'actualizarEstado']);
});
