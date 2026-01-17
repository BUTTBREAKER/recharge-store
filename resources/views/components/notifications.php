<?php

use Leaf\Http\Session;

$notificationLink ??= 'javascript:';
$notificationCount ??= 0;

?>

<a
    href="<?= $notificationLink ?>"
    class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-muted flex items-center justify-between transition-colors">
    <span>
        <?= Session::get('user_role') === 'admin' ? 'Recargas Pendientes' : 'Mis Notificaciones' ?>
    </span>
    <?php if ($notificationCount > 0) : ?>
        <span class="bg-destructive text-destructive-foreground text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
            <?= $notificationCount ?>
        </span>
    <?php endif; ?>
</a>
