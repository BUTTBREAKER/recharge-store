<?php

use Leaf\Http\Session;

$notificationLink ??= 'javascript:';
$notificationCount ??= 0;

?>

<a
    href="<?= $notificationLink ?>"
    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50 flex items-center justify-between">
    <span>
        <?= Session::get('user_role') === 'admin' ? 'Recargas Pendientes' : 'Mis Notificaciones' ?>
    </span>
    <?php if ($notificationCount > 0) : ?>
        <span class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
            <?= $notificationCount ?>
        </span>
    <?php endif; ?>
</a>
