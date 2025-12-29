<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900">Gestión de Recargas</h1>
        <p class="text-gray-500 mt-1">Verifica pagos y completa recargas pendientes</p>
    </div>
    <a href="/admin/dashboard" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al Dashboard
    </a>
</div>

<?php if(isset($_GET['success'])): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-700 font-medium">
            <?php if($_GET['success'] == 'verified'): ?>Pago verificado correctamente<?php endif; ?>
            <?php if($_GET['success'] == 'completed'): ?>Recarga completada correctamente<?php endif; ?>
            <?php if($_GET['success'] == 'rejected'): ?>Pago rechazado<?php endif; ?>
            <?php if($_GET['success'] == 'updated'): ?>Estado del pedido actualizado correctamente<?php endif; ?>
        </p>
    </div>
</div>
<?php endif; ?>

<!-- Tabs de Filtro -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <a href="/admin/orders?estado=pendiente" class="px-5 py-2.5 rounded-xl text-sm font-bold transition relative <?= $filtro == 'pendiente' ? 'bg-yellow-100 text-yellow-700 shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            Pendientes
            <?php if($contadores['pendiente'] > 0): ?>
                <span class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                    <?= $contadores['pendiente'] ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="/admin/orders?estado=confirmado" class="px-5 py-2.5 rounded-xl text-sm font-bold transition relative <?= $filtro == 'confirmado' ? 'bg-blue-100 text-blue-700 shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            Confirmados
            <?php if($contadores['confirmado'] > 0): ?>
                <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                    <?= $contadores['confirmado'] ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="/admin/orders?estado=realizada" class="px-5 py-2.5 rounded-xl text-sm font-bold transition relative <?= $filtro == 'realizada' ? 'bg-green-100 text-green-700 shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            Realizadas
            <?php if($contadores['realizada'] > 0): ?>
                <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                    <?= $contadores['realizada'] ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="/admin/orders?estado=cancelado" class="px-5 py-2.5 rounded-xl text-sm font-bold transition relative <?= $filtro == 'cancelado' ? 'bg-red-100 text-red-700 shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            Cancelados
            <?php if($contadores['cancelado'] > 0): ?>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                    <?= $contadores['cancelado'] ?>
                </span>
            <?php endif; ?>
        </a>
    </div>
</div>

<!-- Tabla de Pedidos -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 font-bold">ID</th>
                    <th class="px-6 py-4 font-bold">Juego / Paquete</th>
                    <th class="px-6 py-4 font-bold">Jugador</th>
                    <th class="px-6 py-4 font-bold">Pago</th>
                    <th class="px-6 py-4 font-bold">Monto</th>
                    <th class="px-6 py-4 font-bold">Fecha</th>
                    <th class="px-6 py-4 font-bold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($pedidos as $p): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-mono text-sm text-gray-500">#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center mr-3 text-lg">
                                <?= $p['juego'] == 'Mobile Legends' ? '📱' : '🎮' ?>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900"><?= $p['juego'] ?></div>
                                <div class="text-xs text-gray-500 font-medium"><?= $p['paquete'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900"><?= $p['player_id'] ?></div>
                        <div class="text-xs text-gray-500">Server: <?= $p['server_id'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-bold <?= $p['metodo_pago'] == 'pagomovil' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' ?>">
                            <?= $p['metodo_pago'] == 'pagomovil' ? '📱 Pago Móvil' : '₿ Binance' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">$<?= number_format($p['monto'], 2) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= date('d/m/Y', strtotime($p['fecha'])) ?><br>
                        <span class="text-xs text-gray-400"><?= date('H:i', strtotime($p['fecha'])) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <?php if($p['estado'] == 'pendiente'): ?>
                                <form action="/admin/orders/verify/<?= $p['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Verificar este pago?');">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Verificar
                                    </button>
                                </form>
                                <form action="/admin/orders/reject/<?= $p['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Rechazar este pago? Esta acción cancelará el pedido.');">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Rechazar
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if($p['estado'] == 'confirmado'): ?>
                                <form action="/admin/orders/complete/<?= $p['id'] ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Marcar esta recarga como completada?');">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                        Completar
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <a href="/admin/pedido/<?= $p['id'] ?>" class="text-gray-400 hover:text-violet-600 transition p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if(empty($pedidos)): ?>
            <div class="text-center py-16">
                <div class="text-6xl mb-4 grayscale opacity-20">
                    <?php if($filtro == 'pendiente'): ?>⏳<?php endif; ?>
                    <?php if($filtro == 'confirmado'): ?>🔄<?php endif; ?>
                    <?php if($filtro == 'realizada'): ?>✅<?php endif; ?>
                    <?php if($filtro == 'cancelado'): ?>❌<?php endif; ?>
                </div>
                <h3 class="text-gray-500 font-medium">No hay pedidos en estado "<?= ucfirst($filtro) ?>"</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Información de Estados -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-xl">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div class="text-sm text-yellow-700">
                <p class="font-bold mb-1">Pendiente</p>
                <p>Esperando verificación de pago. Revisa el comprobante y verifica o rechaza.</p>
            </div>
        </div>
    </div>
    
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div class="text-sm text-blue-700">
                <p class="font-bold mb-1">Confirmado</p>
                <p>Pago verificado. Procede a realizar la recarga en el juego y márcala como completada.</p>
            </div>
        </div>
    </div>
</div>
