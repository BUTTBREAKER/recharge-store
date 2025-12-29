<div class="mb-8 animate-fade-in">
    <a href="/admin/dashboard" class="text-gray-500 hover:text-violet-600 font-medium flex items-center transition-colors w-fit">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al dashboard
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
            <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-6">
                <div>
                     <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pedido</span>
                    <h2 class="text-3xl font-extrabold text-gray-900">#<?= str_pad($pedido['id'], 5, '0', STR_PAD_LEFT) ?></h2>
                </div>
                 <span class="px-4 py-2 rounded-xl text-sm font-bold 
                    <?= $pedido['estado'] == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' ?>
                    <?= $pedido['estado'] == 'confirmado' ? 'bg-blue-100 text-blue-700' : '' ?>
                    <?= $pedido['estado'] == 'realizada' ? 'bg-green-100 text-green-700' : '' ?>
                    <?= $pedido['estado'] == 'cancelado' ? 'bg-red-100 text-red-700' : '' ?>
                ">
                    <?= ucfirst($pedido['estado']) ?>
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Juego & Paquete</span>
                    <div class="font-bold text-gray-900 text-lg"><?= $pedido['juego'] ?></div>
                    <div class="text-violet-600 font-medium"><?= $pedido['paquete'] ?></div>
                </div>
                 <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Datos Jugador</span>
                    <div class="font-mono text-gray-900 text-lg"><?= $pedido['player_id'] ?></div>
                    <div class="text-gray-500 text-sm">Server: <?= $pedido['server_id'] ?></div>
                </div>
                 <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Monto Total</span>
                    <div class="font-bold text-gray-900 text-xl">$<?= number_format($pedido['monto'], 2) ?> USD</div>
                </div>
                 <div class="bg-gray-50 p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Contacto</span>
                    <div class="font-bold text-gray-900"><?= $pedido['telefono'] ?></div>
                     <div class="text-gray-400 text-xs"><?= $pedido['fecha'] ?></div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Gestionar Estado
                </h3>
                <form action="/admin/pedido/actualizar" method="POST" class="bg-gray-50 p-2 rounded-2xl flex gap-2">
                    <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                    <select name="estado" class="flex-grow p-3 bg-white border-0 rounded-xl outline-none shadow-sm focus:ring-2 focus:ring-violet-200 font-medium text-gray-700">
                        <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>🟡 Pendiente</option>
                        <option value="confirmado" <?= $pedido['estado'] == 'confirmado' ? 'selected' : '' ?>>🔵 Confirmado (Pago OK)</option>
                        <option value="realizada" <?= $pedido['estado'] == 'realizada' ? 'selected' : '' ?>>🟢 Realizada (Entregado)</option>
                        <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>🔴 Cancelado</option>
                    </select>
                    <button type="submit" class="bg-violet-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-violet-700 transition shadow-lg shadow-violet-200">
                        Actualizar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Proof -->
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 h-fit sticky top-24">
            <h2 class="text-xl font-bold mb-6 text-gray-900">Comprobante de Pago</h2>
            <?php if($pago): ?>
                <div class="space-y-6">
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                         <div class="w-10 h-10 <?= $pago['provider'] == 'binance' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600' ?> rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Método</div>
                             <div class="font-bold capitalize"><?= $pago['provider'] ?></div>
                        </div>
                    </div>

                    <?php if($pago['referencia']): ?>
                    <div class="block">
                         <span class="text-xs text-gray-500 uppercase font-bold">Referencia</span>
                         <div class="font-mono bg-gray-100 p-2 rounded-lg mt-1 text-center select-all"><?= $pago['referencia'] ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if($pago['comprobante']): ?>
                         <div class="group relative cursor-pointer" onclick="window.open('/<?= $pago['comprobante'] ?>', '_blank')">
                            <span class="text-xs text-gray-500 uppercase font-bold mb-2 block">Imagen Adjunta</span>
                            <div class="relative overflow-hidden rounded-2xl border-2 border-gray-100 group-hover:border-violet-200 transition-all">
                                <img src="/<?= $pago['comprobante'] ?>" class="w-full h-48 object-cover transform group-hover:scale-105 transition duration-500" alt="Comprobante">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center">
                                    <span class="opacity-0 group-hover:opacity-100 bg-white/90 px-3 py-1 rounded-full text-xs font-bold shadow-sm backdrop-blur-sm">Ver Completo</span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-6 border-2 border-dashed border-gray-200 rounded-2xl text-center">
                            <span class="text-gray-400 text-sm">Sin comprobante visual</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl grayscale">🏺</div>
                    <p class="text-gray-500 text-sm italic">El cliente aún no ha reportado el pago.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
