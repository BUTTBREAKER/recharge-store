<?php

use Leaf\Http\Session;
use RECHARGE\models\Pedido;

?>

<div class="hidden md:flex space-x-8 items-center">
    <a href="/" class="text-gray-600 hover:text-violet-600 font-medium transition">Inicio</a>
    <a href="/#games" class="text-gray-600 hover:text-violet-600 font-medium transition">Juegos</a>

    <?php if (Session::has('user_id')) :
        // Calcular notificaciones
        $pedidoModel = new Pedido();
        if (Session::has('user_role') && Session::get('user_role') === 'admin') {
            $notificationCount = $pedidoModel->contarPorEstado('pendiente');
            $notificationLink = '/admin/orders?estado=pendiente';
        } else {
            $notificationCount = $pedidoModel->contarActivosPorUsuario(Session::get('user_id'));
            $notificationLink = '/notifications';
        } ?>
        <!-- Notification Bell -->
        <a href="<?= $notificationLink ?>" class="relative text-gray-600 hover:text-violet-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
            <?php if ($notificationCount > 0) : ?>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                    <?= $notificationCount > 9 ? '9+' : $notificationCount ?>
                </span>
            <?php endif; ?>
        </a>

        <?php if (Session::has('user_role') && Session::get('user_role') === 'admin') : ?>
            <a
                href="/admin/dashboard"
                class="text-violet-600 font-bold hover:text-violet-800 transition">
                Panel Admin
            </a>
        <?php endif; ?>

        <div class="relative group">
            <button class="flex items-center text-gray-700 hover:text-violet-600 font-medium transition">
                <span class="mr-2">
                    Hola, <?= htmlspecialchars(Session::get('user_name') ?? 'Usuario') ?>
                </span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7">
                    </path>
                </svg>
            </button>
            <div class="absolute right-0 w-48 bg-white rounded-xl shadow-lg py-2 mt-2 hidden group-hover:block border border-gray-100 animate-fade-in z-50">
                <?php if (Session::has('user_role') && Session::get('user_role') === 'admin') : ?>
                    <a
                        href="/admin/profile"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-50">
                        Mi Perfil
                    </a>
                <?php else : ?>
                    <a
                        href="/profile"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-50">
                        Mi Perfil
                    </a>
                <?php endif; ?>
                <a
                    href="/logout"
                    class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    <?php else : ?>
        <a
            href="/login"
            class="text-gray-500 hover:text-violet-600 text-sm font-medium transition flex items-center">
            Iniciar Sesión
        </a>
        <a
            href="/register"
            class="bg-violet-100 text-violet-700 px-4 py-2 rounded-full font-semibold hover:bg-violet-200 transition shadow-sm text-sm">
            Unirse
        </a>
    <?php endif ?>

    <!-- Dark Mode Toggle -->
    <div class="dark-mode-toggle" onclick="toggleDarkMode()" title="Cambiar tema">
        <div class="dark-mode-toggle-circle">
            <span class="text-xs">🌙</span>
        </div>
    </div>

    <a
        href="/#games"
        class="bg-gray-900 text-white px-4 py-2 rounded-full font-bold hover:bg-black transition shadow-lg flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 10V3L4 14h7v7l9-11h-7z">
            </path>
        </svg>
        Recargar
    </a>
</div>
