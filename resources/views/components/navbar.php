<?php

// ...

?>

<nav class="fixed w-full z-50 glass-nav border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <?php Flight::render('components/logo') ?>
            <?php Flight::render('components/desktop-menu') ?>
            <?php Flight::render('components/mobile-menu-button') ?>
        </div>
    </div>
    <?php Flight::render('components/mobile-menu') ?>
</nav>
