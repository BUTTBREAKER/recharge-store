<?php
// Game Create View
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Nuevo Juego</h1>
        <p class="text-muted-foreground mt-1">Añadir un nuevo juego a la tienda</p>
    </div>
    <a href="./admin/games" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a Juegos
    </a>
</div>

<div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden max-w-2xl">
    <div class="bg-gradient-to-r from-primary to-accent px-6 py-4">
        <h2 class="text-lg font-bold text-primary-foreground flex items-center">
            <span class="mr-2">🎮</span>
            Información del Juego
        </h2>
    </div>
    
    <form action="./admin/games/store" method="POST" class="p-6 space-y-5">
        <?php csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-bold text-foreground mb-2">Icono</label>
                <input 
                    type="text" 
                    name="icono" 
                    value="🎮"
                    placeholder="🎮"
                    class="w-full px-4 py-3 text-center text-2xl bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                >
                <p class="text-xs text-muted-foreground mt-1">Emoji</p>
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-foreground mb-2">Nombre del Juego</label>
                <input 
                    type="text" 
                    name="nombre" 
                    placeholder="Ej: Mobile Legends"
                    class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                    required
                >
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-foreground mb-2">Slug (URL)</label>
            <input 
                type="text" 
                name="slug" 
                placeholder="mobile-legends"
                class="w-full px-4 py-3 font-mono bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                required
            >
            <p class="text-xs text-muted-foreground mt-1">Se usará en la URL: /juego/<strong>slug</strong></p>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-foreground mb-2">Descripción (opcional)</label>
            <textarea 
                name="descripcion" 
                rows="3"
                placeholder="Breve descripción del juego..."
                class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground resize-none"
            ></textarea>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-foreground mb-2">URL de Imagen (opcional)</label>
            <input 
                type="text" 
                name="imagen" 
                placeholder="https://ejemplo.com/imagen.jpg"
                class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
            >
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
        
        <div class="flex items-center">
            <input type="checkbox" name="activo" id="activo" value="1" checked class="w-5 h-5 text-primary rounded border-border focus:ring-primary">
            <label for="activo" class="ml-3 text-sm font-medium text-foreground">Juego activo (visible en tienda)</label>
        </div>
        
        <div class="pt-4 border-t border-border flex gap-3">
            <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Crear Juego
            </button>
            <a href="./admin/games" class="bg-muted hover:bg-muted/80 text-muted-foreground font-bold py-3 px-6 rounded-xl transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
