<?php

use Leaf\Http\Session;

?>

<div
    class="md:hidden bg-card border-t border-border/40 absolute w-full z-50 shadow-xl"
    :class="{ hidden: !mobileMenuOpen }">
    <div class="px-4 pt-2 pb-4 space-y-2">
        <?php foreach ($links ?? [] as $link) : ?>
            <?php Flight::render('components/mobile-menu-link', $link) ?>
        <?php endforeach ?>

        <?php if (Session::has('user_id')) : ?>
            <?php if (Session::get('user_role') === 'admin') : ?>
                <a
                    href="./admin/dashboard"
                    class="block px-3 py-2 rounded-md text-base font-bold text-primary bg-primary/10">
                    Panel Admin
                </a>
            <?php endif ?>

            <div class="border-t border-border my-2 pt-2">
                <p class="px-3 text-sm text-muted-foreground mb-2">Cuenta</p>

                <span class="block px-3 py-2 text-foreground font-bold">
                    <?= Session::get('user_name') ?? 'Usuario' ?>
                </span>

                <?php Flight::render('components/notifications', compact('notificationLink', 'notificationCount')) ?>

                <a
                    href="<?= Session::get('user_role') === 'admin' ? './admin/profile' : './profile' ?>"
                    class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-muted">
                    Mi Perfil
                </a>

                <a
                    href="./logout"
                    class="block px-3 py-2 rounded-md text-base font-medium text-destructive hover:bg-destructive/10">
                    Cerrar Sesión
                </a>
            </div>
        <?php else : ?>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <a
                    href="./login"
                    class="text-center py-3 border border-border rounded-xl font-bold text-foreground">
                    Entrar
                </a>

                <a
                    href="./register"
                    class="text-center py-3 bg-gradient-to-r from-primary to-accent text-primary-foreground rounded-xl font-bold shadow-lg shadow-primary/20">
                    Unirse
                </a>
            </div>
        <?php endif ?>
    </div>
</div>
