<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Gestión de Precios</h1>
        <p class="text-muted-foreground mt-1">Administra los precios de los paquetes de recarga</p>
    </div>
    <div class="flex gap-3">
        <a href="./admin/products/create" class="bg-primary text-primary-foreground hover:bg-primary/90 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nuevo Producto
        </a>
        <a href="./admin/dashboard" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Dashboard
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])) : ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-700 font-medium">Precio actualizado correctamente</p>
    </div>
</div>
<?php endif; ?>

<!-- Filtros -->
<!-- Filtros -->
<div class="bg-card rounded-2xl shadow-sm border border-border p-4 mb-6">
    <div class="flex gap-3">
        <a href="./admin/prices" class="px-4 py-2 rounded-lg text-sm font-bold transition <?= !$juegoFiltro ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground hover:bg-muted/80' ?>">
            Todos los Juegos
        </a>
        <a href="./admin/prices?juego=Mobile Legends" class="px-4 py-2 rounded-lg text-sm font-bold transition <?= $juegoFiltro == 'Mobile Legends' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground hover:bg-muted/80' ?>">
            Mobile Legends
        </a>
    </div>
</div>

<!-- Tabla de Productos -->
<div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-muted/50 text-muted-foreground text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 font-bold">ID</th>
                    <th class="px-6 py-4 font-bold">Juego</th>
                    <th class="px-6 py-4 font-bold">Producto</th>
                    <th class="px-6 py-4 font-bold">Cantidad</th>
                    <th class="px-6 py-4 font-bold">Precio Actual</th>
                    <th class="px-6 py-4 font-bold">Estado</th>
                    <th class="px-6 py-4 font-bold text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($productos as $p) : ?>
                <tr class="hover:bg-muted/50 transition">
                    <td class="px-6 py-4 font-mono text-sm text-muted-foreground">#<?= $p['id'] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center mr-2 text-sm">
                                <?= $p['juego'] == 'Mobile Legends' ? '📱' : '🎮' ?>
                            </div>
                            <span class="font-medium text-foreground"><?= $p['juego'] ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-foreground"><?= $p['nombre'] ?></div>
                    </td>
                    <td class="px-6 py-4 text-muted-foreground">
                        <span class="bg-muted px-2 py-1 rounded-md text-sm font-medium"><?= number_format($p['cantidad']) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <form action="./admin/prices/update" method="POST" class="flex items-center gap-2">
                            <?php csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground font-bold">$</span>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="precio" 
                                    value="<?= $p['precio'] ?>" 
                                    class="pl-7 pr-3 py-2 bg-input border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary font-bold text-foreground w-28"
                                    required
                                >
                            </div>
                            <button type="submit" class="bg-primary hover:bg-primary/90 text-primary-foreground px-4 py-2 rounded-lg font-bold transition text-sm">
                                Actualizar
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $p['activo'] ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-muted text-muted-foreground border border-border' ?>">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 <?= $p['activo'] ? 'bg-green-500' : 'bg-muted-foreground' ?>"></span>
                            <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="./admin/products/edit/<?= $p['id'] ?>" class="text-muted-foreground hover:text-primary transition p-2" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="./admin/prices/toggle" method="POST" class="inline" onsubmit="return confirm('¿Cambiar estado del producto?');">
                                <?php csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="text-muted-foreground hover:text-primary transition p-2" title="Toggle Estado">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Información -->
<div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        <div class="text-sm text-blue-700">
            <p class="font-bold mb-1">Información sobre precios</p>
            <ul class="list-disc list-inside space-y-1 text-blue-600">
                <li>Los cambios de precios se aplican inmediatamente en la tienda</li>
                <li>Los productos inactivos no se mostrarán a los clientes</li>
                <li>Se recomienda mantener precios competitivos y actualizados</li>
            </ul>
        </div>
    </div>
</div>
