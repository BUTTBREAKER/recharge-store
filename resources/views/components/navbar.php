<?php

use Leaf\Http\Session;
use RECHARGE\models\Pedido;

?>

<nav class="fixed w-full z-50 glass-nav border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location='/'">
                <span class="text-3xl animate-float">💎</span>
                <h1 class="text-xl font-bold tracking-tight text-gray-900">
                    Sisifo<span class="text-violet-500">Store</span>
                </h1>
            </div>

            <!-- Desktop Menu -->
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

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full z-50 shadow-xl">
        <div class="px-4 pt-2 pb-4 space-y-2">
            <a
                href="/"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-violet-600 hover:bg-violet-50">
                Inicio
            </a>
            <a
                href="/#games"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-violet-600 hover:bg-violet-50">
                Juegos
            </a>

            <?php if (Session::has('user_id')) : ?>
                <?php if (Session::has('user_role') && Session::get('user_role') === 'admin') : ?>
                    <a
                        href="/admin/dashboard"
                        class="block px-3 py-2 rounded-md text-base font-bold text-violet-600 bg-violet-50">
                        Panel Admin
                    </a>
                <?php endif ?>
                <div class="border-t border-gray-100 my-2 pt-2">
                    <p class="px-3 text-sm text-gray-400 mb-2">Cuenta</p>
                    <span class="block px-3 py-2 text-gray-800 font-bold">
                        <?= htmlspecialchars(Session::get('user_name') ?? 'Usuario') ?>
                    </span>

                    <!-- Notifications -->
                    <?php if (Session::has('user_role') && Session::get('user_role') === 'admin') : ?>
                        <a
                            href="/admin/orders?estado=pendiente"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50 flex items-center justify-between">
                            <span>Recargas Pendientes</span>
                            <?php if ($notificationCount > 0) : ?>
                                <span class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    <?= $notificationCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php else : ?>
                        <a
                            href="/notifications"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50 flex items-center justify-between">
                            <span>Mis Notificaciones</span>
                            <?php if ($notificationCount > 0) : ?>
                                <span class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    <?= $notificationCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <?php if (Session::has('user_role') && Session::get('user_role') === 'admin') : ?>
                        <a
                            href="/admin/profile"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50">
                            Mi Perfil
                        </a>
                    <?php else : ?>
                        <a
                            href="/profile"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50">
                            Mi Perfil
                        </a>
                    <?php endif; ?>
                    <a
                        href="/logout"
                        class="block px-3 py-2 rounded-md text-base font-medium text-red-500 hover:bg-red-50">
                        Cerrar Sesión
                    </a>
                </div>
            <?php else : ?>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <a
                        href="/login"
                        class="text-center py-3 border border-gray-200 rounded-xl font-bold text-gray-600">
                        Entrar
                    </a>
                    <a
                        href="/register"
                        class="text-center py-3 bg-violet-600 text-white rounded-xl font-bold shadow-lg shadow-violet-200">
                        Unirse
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    // Dark mode toggle
    function toggleDarkMode() {
        const body = document.body;
        const isDark = body.classList.contains('dark-mode');

        if (isDark) {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            localStorage.setItem('theme', 'light');
        } else {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.body.classList.remove('light-mode');
            document.body.classList.add('dark-mode');
        }
    });
</script>
