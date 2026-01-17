<?php
// Game Edit View
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Editar Juego</h1>
        <p class="text-muted-foreground mt-1">Modificar <?= htmlspecialchars($juego['nombre']) ?></p>
    </div>
    <a href="./admin/games" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a Juegos
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl max-w-3xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-500 font-medium">Juego actualizado correctamente</p>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
    <!-- Formulario de edición -->
    <div class="lg:col-span-2">
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent px-6 py-4">
                <h2 class="text-lg font-bold text-primary-foreground flex items-center">
                    <span class="mr-2"><?= $juego['icono'] ?? '🎮' ?></span>
                    Información del Juego
                </h2>
            </div>
            
            <form action="./admin/games/update" method="POST" class="p-6 space-y-5">
                <?php csrf_field() ?>
                <input type="hidden" name="id" value="<?= $juego['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-foreground mb-2">Icono</label>
                        <input 
                            type="text" 
                            name="icono" 
                            value="<?= htmlspecialchars($juego['icono'] ?? '🎮') ?>"
                            class="w-full px-4 py-3 text-center text-2xl bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                        >
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-foreground mb-2">Nombre del Juego</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            value="<?= htmlspecialchars($juego['nombre']) ?>"
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
                        value="<?= htmlspecialchars($juego['slug']) ?>"
                        class="w-full px-4 py-3 font-mono bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Descripción</label>
                    <textarea 
                        name="descripcion" 
                        rows="3"
                        class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground resize-none"
                    ><?= htmlspecialchars($juego['descripcion'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">URL de Imagen</label>
                    <input 
                        type="text" 
                        name="imagen" 
                        value="<?= htmlspecialchars($juego['imagen'] ?? '') ?>"
                        class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Orden</label>
                    <input 
                        type="number" 
                        name="orden" 
                        value="<?= $juego['orden'] ?? 0 ?>"
                        min="0"
                        class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                    >
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="activo" id="activo" value="1" <?= $juego['activo'] ? 'checked' : '' ?> class="w-5 h-5 text-primary rounded border-border focus:ring-primary">
                    <label for="activo" class="ml-3 text-sm font-medium text-foreground">Juego activo</label>
                </div>
                
                <div class="pt-4 border-t border-border flex gap-3">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Cambios
                    </button>
                    <a href="./admin/games" class="bg-muted hover:bg-muted/80 text-muted-foreground font-bold py-3 px-6 rounded-xl transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Panel lateral -->
    <div class="lg:col-span-1">
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
            <div class="bg-muted/50 px-6 py-4 border-b border-border">
                <h3 class="text-lg font-bold text-foreground">Acciones</h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Preview -->
                <div class="p-4 bg-muted rounded-xl text-center">
                    <span class="text-5xl"><?= $juego['icono'] ?? '🎮' ?></span>
                    <p class="font-bold text-foreground mt-2"><?= htmlspecialchars($juego['nombre']) ?></p>
                    <p class="text-xs text-muted-foreground font-mono">/juego/<?= htmlspecialchars($juego['slug']) ?></p>
                </div>
                
                <!-- Estado -->
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-2">Estado actual</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold <?= $juego['activo'] ? 'bg-green-500/10 text-green-500' : 'bg-muted text-muted-foreground' ?>">
                        <span class="w-2 h-2 rounded-full mr-2 <?= $juego['activo'] ? 'bg-green-500' : 'bg-muted-foreground' ?>"></span>
                        <?= $juego['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </div>
                
                <!-- Eliminar -->
                <div class="pt-4 border-t border-border">
                    <form action="./admin/games/delete" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este juego? Los productos asociados NO se eliminarán.');">
                        <?php csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $juego['id'] ?>">
                        <button type="submit" class="w-full bg-destructive/10 hover:bg-destructive/20 text-destructive font-bold py-3 px-6 rounded-xl transition flex items-center justify-center border border-destructive/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar Juego
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
