<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Mis Notificaciones</h1>
            <p class="text-gray-500 mt-1">Estado de tus recargas</p>
        </div>
        <a href="/" class="bg-violet-100 text-violet-700 hover:bg-violet-200 px-5 py-2.5 rounded-xl font-bold transition">
            Volver a la Tienda
        </a>
    </div>

    <?php if (empty($pedidos)) : ?>
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-16 text-center">
        <div class="text-6xl mb-4 opacity-20">📭</div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes recargas aún</h3>
        <p class="text-gray-500 mb-6">Explora nuestros paquetes y realiza tu primera recarga</p>
        <a href="/juego/mobile-legends" class="inline-block bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-xl font-bold transition">
            Ver Paquetes
        </a>
    </div>
    <?php else : ?>
    <!-- Timeline de Pedidos -->
    <div class="space-y-6">
        <?php foreach ($pedidos as $pedido) : ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transition hover:shadow-xl">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-2xl mr-4">
                            <?= $pedido['juego'] == 'Mobile Legends' ? '📱' : '🎮' ?>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900"><?= $pedido['paquete'] ?></h3>
                            <p class="text-sm text-gray-500"><?= $pedido['juego'] ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">$<?= number_format($pedido['monto'], 2) ?></p>
                        <p class="text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></p>
                    </div>
                </div>

                <!-- Estado del Pedido -->
                <div class="mt-6 p-4 rounded-xl
                    <?= $pedido['estado'] == 'pendiente' ? 'bg-yellow-50 border-l-4 border-yellow-500' : '' ?>
                    <?= $pedido['estado'] == 'confirmado' ? 'bg-blue-50 border-l-4 border-blue-500' : '' ?>
                    <?= $pedido['estado'] == 'realizada' ? 'bg-green-50 border-l-4 border-green-500' : '' ?>
                    <?= $pedido['estado'] == 'cancelado' ? 'bg-red-50 border-l-4 border-red-500' : '' ?>
                ">
                    <div class="flex items-center justify-between">
                        <div class="flex-items-center">
                            <?php if ($pedido['estado'] == 'pendiente') : ?>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-white mr-3 animate-pulse">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-yellow-900">Esperando Verificación</h4>
                                        <p class="text-sm text-yellow-700">Estamos verificando tu pago. Esto puede tomar unos minutos.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($pedido['estado'] == 'confirmado') : ?>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white mr-3 animate-pulse">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-blue-900">Pago Verificado</h4>
                                        <p class="text-sm text-blue-700">Tu pago fue verificado. Estamos procesando tu recarga.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($pedido['estado'] == 'realizada') : ?>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white mr-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-green-900">¡Recarga Completada!</h4>
                                        <p class="text-sm text-green-700">Tu recarga fue entregada exitosamente. Revisa tu cuenta en el juego.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($pedido['estado'] == 'cancelado') : ?>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white mr-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-red-900">Pago No Verificado</h4>
                                        <p class="text-sm text-red-700">No pudimos verificar tu pago. Contacta con soporte para más información.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID del Pedido</p>
                        <p class="font-mono text-sm font-bold text-gray-900">#<?= str_pad($pedido['id'], 5, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">ID del Jugador</p>
                        <p class="font-mono text-sm font-bold text-gray-900"><?= $pedido['player_id'] ?> (<?= $pedido['server_id'] ?>)</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Método de Pago</p>
                        <p class="text-sm font-bold text-gray-900">
                            <?= $pedido['metodo_pago'] == 'pagomovil' ? '📱 Pago Móvil' : '₿ Binance Pay' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Teléfono</p>
                        <p class="text-sm font-bold text-gray-900"><?= $pedido['telefono'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <!-- Información de Ayuda -->
    <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div class="text-sm text-blue-700">
                <p class="font-bold mb-2">Estados de tu recarga:</p>
                <ul class="space-y-1">
                    <li><strong>Pendiente:</strong> Esperando verificación de pago (5-15 min)</li>
                    <li><strong>Confirmado:</strong> Pago verificado, procesando recarga (5-30 min)</li>
                    <li><strong>Realizada:</strong> ¡Recarga completada! Revisa tu cuenta en el juego</li>
                    <li><strong>Cancelado:</strong> Pago no verificado, contacta soporte</li>
                </ul>
            </div>
        </div>
    </div>
</div>
