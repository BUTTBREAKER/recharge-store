<div class="max-w-xl mx-auto text-center">
    <div class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
        <div class="mb-6">
            <?php if ($pedido['estado'] == 'pendiente') : ?>
                <div class="w-20 h-20 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">⏳</div>
                <h1 class="text-2xl font-bold text-gray-900">Pedido Pendiente</h1>
                <p class="text-gray-600 mt-2">Estamos verificando tu pago. Esto puede tomar unos minutos.</p>
            <?php elseif ($pedido['estado'] == 'confirmado') : ?>
                <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">✅</div>
                <h1 class="text-2xl font-bold text-gray-900">Pago Confirmado</h1>
                <p class="text-gray-600 mt-2">Tu pago ha sido validado. Procesando tu recarga...</p>
            <?php elseif ($pedido['estado'] == 'completado') : ?>
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">🎮</div>
                <h1 class="text-2xl font-bold text-gray-900">Recarga Realizada</h1>
                <p class="text-gray-600 mt-2">¡Diamantes enviados! Revisa tu cuenta en el juego.</p>
            <?php else : ?>
                <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-4xl mb-4">❌</div>
                <h1 class="text-2xl font-bold text-gray-900">Pedido Cancelado</h1>
                <p class="text-gray-600 mt-2">Hubo un problema con tu pedido o fue cancelado.</p>
            <?php endif; ?>
        </div>

        <div class="border-t pt-6 text-left space-y-2">
            <p class="text-sm text-gray-500">ID del Pedido: <span class="font-mono font-bold text-gray-800">#<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></span></p>
            <p class="text-sm text-gray-500">Juego: <span class="font-bold text-gray-800"><?= $pedido['juego'] ?></span></p>
            <p class="text-sm text-gray-500">Paquete: <span class="font-bold text-gray-800"><?= $pedido['paquete'] ?></span></p>
        </div>

        <div class="mt-8">
            <a href="/" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-6 rounded-xl transition">
                Volver a la tienda
            </a>
        </div>
    </div>
</div>
