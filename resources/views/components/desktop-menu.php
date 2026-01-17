<?php

use Leaf\Http\Session;

?>

<div class="hidden md:flex space-x-8 items-center">
    <?php foreach ($links ?? [] as $link) : ?>
        <?php Flight::render('components/desktop-menu-link', $link) ?>
    <?php endforeach ?>

    <?php if (Session::has('user_id')) : ?>
        <?php Flight::render('components/notification-bell', compact('notificationLink', 'notificationCount')) ?>

        <?php if (Session::get('user_role') === 'admin') : ?>
            <a
                href="./admin/dashboard"
                class="text-primary font-bold hover:text-primary/80 transition">
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
            href="./register"
            class="bg-primary/10 text-primary px-4 py-2 rounded-full font-semibold hover:bg-primary/20 transition shadow-sm text-sm">
            Unirse
        </a>
    <?php endif ?>

    <?php Flight::render('components/dark-mode-toggle') ?>

    <a
        href="./#games"
        class="bg-gradient-to-r from-primary to-accent text-primary-foreground px-4 py-2 rounded-full font-bold hover:opacity-90 transition shadow-lg flex items-center gap-2">
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
