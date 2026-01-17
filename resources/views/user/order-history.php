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
        'pendiente' => '<span class="px-3 py-1 bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 rounded-full text-xs font-bold ring-1 ring-yellow-500/20">⏳ Pendiente</span>',
        'confirmado' => '<span class="px-3 py-1 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold ring-1 ring-blue-500/20">✓ Confirmado</span>',
        'realizada' => '<span class="px-3 py-1 bg-green-500/10 text-green-600 dark:text-green-400 rounded-full text-xs font-bold ring-1 ring-green-500/20">✅ Completado</span>',
        'cancelado' => '<span class="px-3 py-1 bg-destructive/10 text-destructive rounded-full text-xs font-bold ring-1 ring-destructive/20">❌ Cancelado</span>',
    ];
    return $badges[$estado] ?? '<span class="px-3 py-1 bg-muted text-muted-foreground rounded-full text-xs font-semibold">' . $estado . '</span>';
}

?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="./profile" class="text-primary hover:text-primary/80 font-medium flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver al Perfil
        </a>
        <h1 class="text-3xl font-bold text-foreground">Historial de Pedidos</h1>
        <p class="text-muted-foreground mt-2">Todos tus pedidos y su estado actual</p>
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
        <div class="bg-card rounded-xl shadow-sm p-6 border border-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Total</p>
                    <p class="text-2xl font-bold text-foreground"><?= $stats['total'] ?></p>
                </div>
                <div class="text-3xl">📦</div>
            </div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl shadow-sm p-6 border border-yellow-100 dark:border-yellow-900/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-200"><?= $stats['pendiente'] ?></p>
                </div>
                <div class="text-3xl">⏳</div>
            </div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow-sm p-6 border border-blue-100 dark:border-blue-900/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-700 dark:text-blue-400">Confirmados</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-200"><?= $stats['confirmado'] ?></p>
                </div>
                <div class="text-3xl">✓</div>
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl shadow-sm p-6 border border-green-100 dark:border-green-900/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700 dark:text-green-400">Completados</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-200"><?= $stats['realizada'] ?></p>
                </div>
                <div class="text-3xl">✅</div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <?php if (empty($pedidos)) : ?>
        <div class="bg-card rounded-2xl shadow-sm p-12 text-center border border-border">
            <div class="text-6xl mb-4 opacity-50 filter grayscale">🛒</div>
            <h3 class="text-xl font-bold text-foreground mb-2">No tienes pedidos aún</h3>
            <p class="text-muted-foreground mb-6">Comienza tu primera recarga de diamantes</p>
            <a href="./" class="inline-block bg-primary text-primary-foreground font-bold py-3 px-6 rounded-xl hover:opacity-90 transition-colors">
                Ver Juegos Disponibles
            </a>
        </div>
    <?php else : ?>
        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">Juego</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">Paquete</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">Player ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">Monto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-muted-foreground uppercase tracking-wider">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php foreach ($pedidos as $pedido) : ?>
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-foreground">
                                    #<?= str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                                    <?= htmlspecialchars($pedido['juego']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-foreground/80">
                                    <?= htmlspecialchars($pedido['paquete']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-muted-foreground">
                                    <?= htmlspecialchars($pedido['player_id']) ?> (<?= htmlspecialchars($pedido['server_id']) ?>)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-foreground">
                                    $<?= number_format($pedido['monto'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= getEstadoBadge($pedido['estado']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-border">
                <?php foreach ($pedidos as $pedido) : ?>
                    <div class="p-6 hover:bg-muted/30 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-mono text-sm text-muted-foreground">#<?= str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) ?></p>
                                <h3 class="font-bold text-foreground"><?= htmlspecialchars($pedido['juego']) ?></h3>
                            </div>
                            <?= getEstadoBadge($pedido['estado']) ?>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Paquete:</span>
                                <span class="font-medium text-foreground"><?= htmlspecialchars($pedido['paquete']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Player ID:</span>
                                <span class="font-mono text-foreground"><?= htmlspecialchars($pedido['player_id']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Monto:</span>
                                <span class="font-bold text-foreground">$<?= number_format($pedido['monto'], 2) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Fecha:</span>
                                <span class="text-foreground/80"><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
