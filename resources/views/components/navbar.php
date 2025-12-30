<?php

use Leaf\Http\Session;
use RECHARGE\models\Pedido;

$links = [
    ['href' => './', 'slot' => 'Inicio'],
    ['href' => './#games', 'slot' => 'Juegos'],
];

// Calcular notificaciones
$pedidoModel = new Pedido();

if (Session::get('user_role') === 'admin') {
    $notificationCount = $pedidoModel->contarPorEstado('pendiente');
    $notificationLink = './admin/orders?estado=pendiente';
} else {
    $notificationCount = $pedidoModel->contarActivosPorUsuario(Session::get('user_id'));
    $notificationLink = './notifications';
}

?>

<nav class="fixed w-full z-50 glass-nav border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
        <?php Flight::render('components/logo') ?>
        <?php Flight::render('components/desktop-menu', compact('links', 'notificationLink', 'notificationCount')) ?>
        <?php Flight::render('components/mobile-menu-button') ?>
    </div>
    <?php Flight::render('components/mobile-menu', compact('links', 'notificationLink', 'notificationCount')) ?>
</nav>
