<?php

use Leaf\Http\Session;

?>

<div class="hidden md:flex space-x-8 items-center">
    <?php foreach ([['href' => './', 'slot' => 'Inicio'], ['href' => './#games', 'slot' => 'Juegos']] as $link) : ?>
        <?php Flight::render('components/desktop-menu-link', $link) ?>
    <?php endforeach ?>

    <?php if (Session::has('user_id')) : ?>
        <?php Flight::render('components/notification-bell') ?>
        <?php if (Session::get('user_role') === 'admin') : ?>
            <a
                href="./admin/dashboard"
                class="text-violet-600 font-bold hover:text-violet-800 transition">
                Panel Admin
            </a>
        <?php endif ?>

        <?php Flight::render('components/user-dropdown') ?>
    <?php else : ?>
        <?php Flight::render('components/desktop-menu-link', [
            'href' => './login',
            'slot' => 'Iniciar Sesión',
        ]) ?>
        <a
            href="/register"
            class="bg-violet-100 text-violet-700 px-4 py-2 rounded-full font-semibold hover:bg-violet-200 transition shadow-sm text-sm">
            Unirse
        </a>
    <?php endif ?>

    <?php Flight::render('components/dark-mode-toggle') ?>

    <a
        href="./#games"
        class="bg-gray-900 text-white px-4 py-2 rounded-full font-bold hover:bg-black transition shadow-lg flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
