<?php

use Leaf\Http\Session;
use App\Models\Pedido;

$links = [
    ['href' => './', 'slot' => 'Inicio'],
    ['href' => './#games', 'slot' => 'Juegos'],
];

// Calcular notificaciones
$pedidoModel = new Pedido();
$notificationModel = new \App\Models\Notificacion();

if (Session::get('user_role') === 'admin') {
    $notificationCount = $pedidoModel->contarPorEstado('pendiente');
    $notificationLink = './admin/orders?estado=pendiente';
} else {
    $notificationCount = $notificationModel->contarSinLeer(Session::get('user_id'));
    $notificationLink = './notifications';
}

?>

<nav class="sticky top-0 z-50 bg-white/80 glass-nav transition-colors duration-300 backdrop-blur-xs border-b border-gray-100">
    <div class="max-w-7xl m-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
        <?php Flight::render('components/logo') ?>
        <?php Flight::render('components/desktop-menu', compact('links', 'notificationLink', 'notificationCount')) ?>
        <?php Flight::render('components/mobile-menu-button') ?>
    </div>
    <?php Flight::render('components/mobile-menu', compact('links', 'notificationLink', 'notificationCount')) ?>
</nav>
