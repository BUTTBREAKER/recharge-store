<?php
// Product Create View
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Nuevo Producto</h1>
        <p class="text-muted-foreground mt-1">Añadir un nuevo paquete de recarga</p>
    </div>
    <a href="./admin/prices" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a Productos
    </a>
</div>

<div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden max-w-2xl">
    <div class="bg-gradient-to-r from-primary to-accent px-6 py-4">
        <h2 class="text-lg font-bold text-primary-foreground flex items-center">
            <span class="mr-2">📦</span>
            Información del Producto
        </h2>
    </div>
    
    <form action="./admin/products/store" method="POST" class="p-6 space-y-5">
        <?php csrf_field() ?>
        
        <div>
            <label class="block text-sm font-bold text-foreground mb-2">Juego</label>
            <select name="juego" required class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-medium text-foreground">
                <option value="">Seleccionar juego...</option>
                <?php foreach ($juegos as $juego): ?>
                <option value="<?= htmlspecialchars($juego) ?>"><?= htmlspecialchars($juego) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-foreground mb-2">Nombre del Producto</label>
            <input 
                type="text" 
                name="nombre" 
                placeholder="Ej: 86 Diamantes"
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
                    placeholder="86"
                    min="1"
                    class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                    required
                >
                <p class="text-xs text-muted-foreground mt-1">Cantidad de diamantes/moneda</p>
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
                        placeholder="1.50"
                        class="w-full pl-8 pr-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                        required
                    >
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Precio Original (Opcional)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground font-bold">$</span>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0.01"
                        name="precio_original" 
                        placeholder="2.00"
                        class="w-full pl-8 pr-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                    >
                </div>
                <p class="text-xs text-muted-foreground mt-1">Para mostrar descuento</p>
            </div>
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Orden de visualización</label>
                <input 
                    type="number" 
                    name="orden" 
                    value="0"
                    min="0"
                    class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                >
            </div>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="activo" id="activo" value="1" checked class="w-5 h-5 text-primary rounded border-border focus:ring-primary">
            <label for="activo" class="ml-3 text-sm font-medium text-foreground">Producto activo (visible en tienda)</label>
        </div>
        
        <div class="pt-4 border-t border-border flex gap-3">
            <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Crear Producto
            </button>
            <a href="./admin/prices" class="bg-muted hover:bg-muted/80 text-muted-foreground font-bold py-3 px-6 rounded-xl transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
