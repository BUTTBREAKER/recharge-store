<?php

use Leaf\Http\Session;

?>

<div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full z-50 shadow-xl">
    <div class="px-4 pt-2 pb-4 space-y-2">
        <?php foreach ($links ?? [] as $link) : ?>
            <?php Flight::render('components/mobile-menu-link', $link) ?>
        <?php endforeach ?>

        <?php if (Session::has('user_id')) : ?>
            <?php if (Session::get('user_role') === 'admin') : ?>
                <a
                    href="./admin/dashboard"
                    class="block px-3 py-2 rounded-md text-base font-bold text-violet-600 bg-violet-50">
                    Panel Admin
                </a>
            <?php endif ?>

            <div class="border-t border-gray-100 my-2 pt-2">
                <p class="px-3 text-sm text-gray-400 mb-2">Cuenta</p>

                <span class="block px-3 py-2 text-gray-800 font-bold">
                    <?= Session::get('user_name') ?? 'Usuario' ?>
                </span>

                <?php Flight::render('components/notifications', compact('notificationLink', 'notificationCount')) ?>

                <a
                    href="<?= Session::get('user_role') === 'admin' ? './admin/profile' : './profile' ?>"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50">
                    Mi Perfil
                </a>

                <a
                    href="./logout"
                    class="block px-3 py-2 rounded-md text-base font-medium text-red-500 hover:bg-red-50">
                    Cerrar Sesión
                </a>
            </div>
        <?php else : ?>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <a
                    href="./login"
                    class="text-center py-3 border border-gray-200 rounded-xl font-bold text-gray-600">
                    Entrar
                </a>

                <a
                    href="./register"
                    class="text-center py-3 bg-violet-600 text-white rounded-xl font-bold shadow-lg shadow-violet-200">
                    Unirse
                </a>
            </div>
        <?php endif ?>
    </div>
</div>
