<div class="max-w-xl mx-auto text-center animate-fade-in">
    <div class="bg-card p-10 rounded-3xl shadow-xl border border-border">
        <div class="mb-6">
            <?php if ($pedido['estado'] == 'pendiente') : ?>
                <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">⏳</div>
                <h1 class="text-2xl font-bold text-foreground">Pedido Pendiente</h1>
                <p class="text-muted-foreground mt-2">Estamos verificando tu pago. Esto puede tomar unos minutos.</p>
            <?php elseif ($pedido['estado'] == 'confirmado') : ?>
                <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">✅</div>
                <h1 class="text-2xl font-bold text-foreground">Pago Confirmado</h1>
                <p class="text-muted-foreground mt-2">Tu pago ha sido validado. Procesando tu recarga...</p>
            <?php elseif ($pedido['estado'] == 'completado') : ?>
                <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">🎮</div>
                <h1 class="text-2xl font-bold text-foreground">Recarga Realizada</h1>
                <p class="text-muted-foreground mt-2">¡Diamantes enviados! Revisa tu cuenta en el juego.</p>
            <?php else : ?>
                <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">❌</div>
                <h1 class="text-2xl font-bold text-foreground">Pedido Cancelado</h1>
                <p class="text-muted-foreground mt-2">Hubo un problema con tu pedido o fue cancelado.</p>
            <?php endif; ?>
        </div>

        <div class="border-t border-border pt-6 text-left space-y-2">
            <p class="text-sm text-muted-foreground">ID del Pedido: <span class="font-mono font-bold text-foreground">#<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></span></p>
            <p class="text-sm text-muted-foreground">Juego: <span class="font-bold text-foreground"><?= $pedido['juego'] ?></span></p>
            <p class="text-sm text-muted-foreground">Paquete: <span class="font-bold text-foreground"><?= $pedido['paquete'] ?></span></p>
        </div>

        <div class="mt-8">
            <a href="./" class="inline-block bg-muted hover:bg-muted/80 text-foreground font-semibold py-2 px-6 rounded-xl transition">
                Volver a la tienda
            </a>
        </div>
    </div>
</div>
