<?php
// Product Edit View
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Editar Producto</h1>
        <p class="text-muted-foreground mt-1">Modificar información del paquete #<?= $producto['id'] ?></p>
    </div>
    <a href="./admin/prices" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a Productos
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl max-w-2xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-500 font-medium">Producto actualizado correctamente</p>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
    <!-- Formulario de edición -->
    <div class="lg:col-span-2">
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent px-6 py-4">
                <h2 class="text-lg font-bold text-primary-foreground flex items-center">
                    <span class="mr-2">✏️</span>
                    Información del Producto
                </h2>
            </div>
            
            <form action="./admin/products/update" method="POST" class="p-6 space-y-5">
                <?php csrf_field() ?>
                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Juego</label>
                    <select name="juego" required class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-medium text-foreground">
                        <?php foreach ($juegos as $juego): ?>
                        <option value="<?= htmlspecialchars($juego) ?>" <?= $producto['juego'] == $juego ? 'selected' : '' ?>><?= htmlspecialchars($juego) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Nombre del Producto</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        value="<?= htmlspecialchars($producto['nombre']) ?>"
                        class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                        required
                    >
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Cantidad</label>
                        <input 
                            type="number" 
                            name="cantidad" 
                            value="<?= $producto['cantidad'] ?>"
                            min="1"
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Precio (USD)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground font-bold">$</span>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0.01"
                                name="precio" 
                                value="<?= $producto['precio'] ?>"
                                class="w-full pl-8 pr-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                                required
                            >
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Precio Original</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground font-bold">$</span>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0.01"
                                name="precio_original" 
                                value="<?= $producto['precio_original'] ?? '' ?>"
                                class="w-full pl-8 pr-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Orden</label>
                        <input 
                            type="number" 
                            name="orden" 
                            value="<?= $producto['orden'] ?? 0 ?>"
                            min="0"
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                        >
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="activo" id="activo" value="1" <?= $producto['activo'] ? 'checked' : '' ?> class="w-5 h-5 text-primary rounded border-border focus:ring-primary">
                    <label for="activo" class="ml-3 text-sm font-medium text-foreground">Producto activo (visible en tienda)</label>
                </div>
                
                <div class="pt-4 border-t border-border flex gap-3">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Cambios
                    </button>
                    <a href="./admin/prices" class="bg-muted hover:bg-muted/80 text-muted-foreground font-bold py-3 px-6 rounded-xl transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Panel de acciones -->
    <div class="lg:col-span-1">
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
            <div class="bg-muted/50 px-6 py-4 border-b border-border">
                <h3 class="text-lg font-bold text-foreground">Acciones</h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Estado -->
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-2">Estado actual</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold <?= $producto['activo'] ? 'bg-green-500/10 text-green-500' : 'bg-muted text-muted-foreground' ?>">
                        <span class="w-2 h-2 rounded-full mr-2 <?= $producto['activo'] ? 'bg-green-500' : 'bg-muted-foreground' ?>"></span>
                        <?= $producto['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </div>
                
                <!-- Info -->
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-1">Creado</p>
                    <p class="font-medium text-foreground"><?= isset($producto['created_at']) ? date('d/m/Y H:i', strtotime($producto['created_at'])) : 'N/A' ?></p>
                </div>
                
                <?php if (isset($producto['updated_at'])): ?>
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-1">Última actualización</p>
                    <p class="font-medium text-foreground"><?= date('d/m/Y H:i', strtotime($producto['updated_at'])) ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Eliminar -->
                <div class="pt-4 border-t border-border">
                    <form action="./admin/products/delete" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.');">
                        <?php csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                        <button type="submit" class="w-full bg-destructive/10 hover:bg-destructive/20 text-destructive font-bold py-3 px-6 rounded-xl transition flex items-center justify-center border border-destructive/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
