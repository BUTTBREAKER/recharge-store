<?php

use Leaf\Http\Session;

$user = null;
if (Session::has('user_id')) {
    $user = [
        'name' => Session::get('user_name'),
        'email' => Session::get('user_email'),
    ];
}

$pedidos = $pedidos ?? [];

// Función helper para badge de estado
function getEstadoBadge($estado)
{
    $badges = [
        'pendiente' => '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Pendiente</span>',
        'confirmado' => '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">✓ Confirmado</span>',
        'realizada' => '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✅ Completado</span>',
        'cancelado' => '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">❌ Cancelado</span>',
    ];
    return $badges[$estado] ?? '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">' . $estado . '</span>';
}

?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="/profile" class="text-violet-600 hover:text-violet-700 font-medium flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver al Perfil
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Historial de Pedidos</h1>
        <p class="text-gray-500 mt-2">Todos tus pedidos y su estado actual</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <?php
        $stats = [
            'total' => count($pedidos),
            'pendiente' => count(array_filter($pedidos, fn($p) => $p['estado'] === 'pendiente')),
            'confirmado' => count(array_filter($pedidos, fn($p) => $p['estado'] === 'confirmado')),
            'realizada' => count(array_filter($pedidos, fn($p) => $p['estado'] === 'realizada')),
        ];
        ?>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?></p>
                </div>
                <div class="text-3xl">📦</div>
            </div>
        </div>
        <div class="bg-yellow-50 rounded-xl shadow-sm p-6 border border-yellow-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-700">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-900"><?= $stats['pendiente'] ?></p>
                </div>
                <div class="text-3xl">⏳</div>
            </div>
        </div>
        <div class="bg-blue-50 rounded-xl shadow-sm p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-700">Confirmados</p>
                    <p class="text-2xl font-bold text-blue-900"><?= $stats['confirmado'] ?></p>
                </div>
                <div class="text-3xl">✓</div>
            </div>
        </div>
        <div class="bg-green-50 rounded-xl shadow-sm p-6 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700">Completados</p>
                    <p class="text-2xl font-bold text-green-900"><?= $stats['realizada'] ?></p>
                </div>
                <div class="text-3xl">✅</div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <?php if (empty($pedidos)) : ?>
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
            <div class="text-6xl mb-4">🛒</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes pedidos aún</h3>
            <p class="text-gray-500 mb-6">Comienza tu primera recarga de diamantes</p>
            <a href="/" class="inline-block bg-violet-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-violet-700 transition-colors">
                Ver Juegos Disponibles
            </a>
        </div>
    <?php else : ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Juego</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Paquete</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Player ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Monto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($pedidos as $pedido) : ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    #<?= str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($pedido['juego']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?= htmlspecialchars($pedido['paquete']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                                    <?= htmlspecialchars($pedido['player_id']) ?> (<?= htmlspecialchars($pedido['server_id']) ?>)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    $<?= number_format($pedido['monto'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= getEstadoBadge($pedido['estado']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-100">
                <?php foreach ($pedidos as $pedido) : ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-mono text-sm text-gray-500">#<?= str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) ?></p>
                                <h3 class="font-bold text-gray-900"><?= htmlspecialchars($pedido['juego']) ?></h3>
                            </div>
                            <?= getEstadoBadge($pedido['estado']) ?>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Paquete:</span>
                                <span class="font-medium text-gray-900"><?= htmlspecialchars($pedido['paquete']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Player ID:</span>
                                <span class="font-mono text-gray-700"><?= htmlspecialchars($pedido['player_id']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Monto:</span>
                                <span class="font-bold text-gray-900">$<?= number_format($pedido['monto'], 2) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Fecha:</span>
                                <span class="text-gray-700"><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
