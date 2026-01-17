<?php
// Preparar datos para Chart.js
$ventasDiariasLabels = [];
$ventasDiariasData = [];
for ($i = 0; $i < 24; $i++) {
    $ventasDiariasLabels[] = $i . ':00';
    $ventasDiariasData[] = 0;
}
foreach ($ventasDiarias as $venta) {
    $ventasDiariasData[$venta['hora']] = floatval($venta['total_ventas']);
}

$ventasSemanalesLabels = [];
$ventasSemanalesData = [];
$diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
foreach ($ventasSemanales as $v) {
    $ventasSemanalesLabels[] = date('d/m', strtotime($v['fecha']));
    $ventasSemanalesData[] = floatval($v['total_ventas']);
}

$ventasMensualesLabels = [];
$ventasMensualesData = [];
foreach ($ventasMensuales as $v) {
    $ventasMensualesLabels[] = 'Día ' . $v['dia'];
    $ventasMensualesData[] = floatval($v['total_ventas']);
}
?>

<!-- Incluir Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Header del Dashboard -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Dashboard Administrativo</h1>
        <p class="text-muted-foreground mt-1">Panel de control y analíticas de ventas</p>
    </div>
    <div class="flex gap-3">
        <a href="/admin/prices" class="bg-primary/5 text-primary border border-primary/20 hover:bg-primary/10 px-5 py-2.5 rounded-xl font-bold transition flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Gestionar Precios
        </a>
        <a href="/admin/payments" class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 px-5 py-2.5 rounded-xl font-bold transition flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Datos de Pago
        </a>
        <a href="/admin/config" class="bg-green-50 text-green-600 border border-green-200 hover:bg-green-100 px-5 py-2.5 rounded-xl font-bold transition flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            💱 Tasa Bs/$
        </a>
    </div>
</div>

<!-- Tarjetas de Resumen -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Ventas -->
    <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-violet-100 text-sm font-medium mb-1">Total Ventas</p>
                <h3 class="text-3xl font-bold">$<?= number_format($resumen['total_ventas'] ?? 0, 2) ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Pedidos Hoy -->
    <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Pedidos Hoy</p>
                <h3 class="text-3xl font-bold"><?= $resumen['pedidos_hoy'] ?? 0 ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Promedio de Venta -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Promedio Venta</p>
                <h3 class="text-3xl font-bold">$<?= number_format($resumen['promedio_venta'] ?? 0, 2) ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Total Pedidos -->
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-100 text-sm font-medium mb-1">Total Pedidos</p>
                <h3 class="text-3xl font-bold"><?= $resumen['total_pedidos'] ?? 0 ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Ventas Diarias -->
    <div class="bg-card rounded-2xl shadow-lg border border-border p-6">
        <h3 class="text-lg font-bold text-foreground mb-4 flex items-center">
            <span class="w-2 h-2 bg-primary rounded-full mr-2"></span>
            Ventas Diarias (Hoy)
        </h3>
        <div class="h-64">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    <!-- Ventas Semanales -->
    <div class="bg-card rounded-2xl shadow-lg border border-border p-6">
        <h3 class="text-lg font-bold text-foreground mb-4 flex items-center">
            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
            Ventas Semanales
        </h3>
        <div class="h-64">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    <!-- Ventas Mensuales -->
    <div class="bg-card rounded-2xl shadow-lg border border-border p-6">
        <h3 class="text-lg font-bold text-foreground mb-4 flex items-center">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
            Ventas Mensuales
        </h3>
        <div class="h-64">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<!-- Últimos Pedidos -->
<div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
    <div class="px-6 py-5 border-b border-border">
        <h3 class="text-lg font-bold text-foreground">Últimos Pedidos</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-muted/50 text-muted-foreground text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 font-bold">ID</th>
                    <th class="px-6 py-4 font-bold">Producto</th>
                    <th class="px-6 py-4 font-bold">Monto</th>
                    <th class="px-6 py-4 font-bold">Estado</th>
                    <th class="px-6 py-4 font-bold">Fecha</th>
                    <th class="px-6 py-4 font-bold text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($ultimosPedidos as $p) : ?>
                <tr class="hover:bg-muted/50 transition">
                    <td class="px-6 py-4 font-mono text-sm text-muted-foreground">#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-foreground"><?= $p['paquete'] ?></div>
                        <div class="text-xs text-muted-foreground"><?= $p['juego'] ?></div>
                    </td>
                    <td class="px-6 py-4 font-bold text-foreground">$<?= number_format($p['monto'], 2) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                            <?= $p['estado'] == 'pendiente' ? 'bg-yellow-500/10 text-yellow-500' : '' ?>
                            <?= $p['estado'] == 'confirmado' ? 'bg-blue-500/10 text-blue-500' : '' ?>
                            <?= $p['estado'] == 'realizada' ? 'bg-green-500/10 text-green-500' : '' ?>
                            <?= $p['estado'] == 'cancelado' ? 'bg-red-500/10 text-red-500' : '' ?>
                        ">
                            <?= ucfirst($p['estado']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-muted-foreground"><?= date('d/m/Y H:i', strtotime($p['fecha'])) ?></td>
                    <td class="px-6 py-4 text-right">
                        <a href="./admin/pedido/<?= $p['id'] ?>" class="text-primary hover:text-primary/80 font-medium text-sm">
                            Ver detalles →
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script para inicializar gráficos -->
<script>
window.chartData = {
    daily: {
        labels: <?= json_encode($ventasDiariasLabels) ?>,
        data: <?= json_encode($ventasDiariasData) ?>
    },
    weekly: {
        labels: <?= json_encode($ventasSemanalesLabels) ?>,
        data: <?= json_encode($ventasSemanalesData) ?>
    },
    monthly: {
        labels: <?= json_encode($ventasMensualesLabels) ?>,
        data: <?= json_encode($ventasMensualesData) ?>
    }
};
</script>
<script src="/js/admin-charts.js"></script>
