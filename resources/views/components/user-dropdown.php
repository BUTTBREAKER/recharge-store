<?php

use Leaf\Http\Session;

?>

<div class="relative group">
    <button class="flex items-center gap-2 text-gray-700 hover:text-violet-600 font-medium transition">
        Hola,
        <?= Session::get('user_name') ?? 'Usuario' ?>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7">
            </path>
        </svg>
    </button>

    <div class="absolute w-48 bg-white rounded-xl shadow-lg py-2 hidden group-hover:block border border-gray-100 animate-fade-in z-50">
        <?php foreach ([['href' => Session::get('user_name') === 'admin' ? './admin/profile' : './profile', 'slot' => 'Mi Perfil'], ['href' => './logout', 'slot' => 'Cerrar Sesión']] as $link) : ?>
            <?php Flight::render('components/user-dropdown-link', $link) ?>
        <?php endforeach ?>
    </div>
</div>
