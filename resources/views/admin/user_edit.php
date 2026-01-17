<?php
// User Edit View
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Editar Usuario</h1>
        <p class="text-muted-foreground mt-1">Modificar información de <?= htmlspecialchars($usuario['name']) ?></p>
    </div>
    <a href="./admin/users" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver a Usuarios
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl max-w-3xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-500 font-medium">Usuario actualizado correctamente</p>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
    <!-- Formulario de edición -->
    <div class="lg:col-span-2">
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
            <div class="bg-gradient-to-r <?= $usuario['role'] === 'admin' ? 'from-red-500 to-orange-500' : 'from-blue-500 to-cyan-500' ?> px-6 py-4">
                <h2 class="text-lg font-bold text-white flex items-center">
                    <span class="mr-2"><?= $usuario['role'] === 'admin' ? '🛡️' : '👤' ?></span>
                    Información del Usuario
                </h2>
            </div>
            
            <form action="./admin/users/update" method="POST" class="p-6 space-y-5">
                <?php csrf_field() ?>
                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                
                <div>
                    <label class="block text-sm font-bold text-muted-foreground mb-2">Nombre Completo (Solo lectura)</label>
                    <div class="w-full px-4 py-3 bg-muted border border-border rounded-xl text-muted-foreground cursor-not-allowed">
                        <?= htmlspecialchars($usuario['name']) ?>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-muted-foreground mb-2">Correo Electrónico (Solo lectura)</label>
                    <div class="w-full px-4 py-3 bg-muted border border-border rounded-xl text-muted-foreground cursor-not-allowed">
                        <?= htmlspecialchars($usuario['email']) ?>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Rol</label>
                    <select name="role" class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground">
                        <option value="user" <?= $usuario['role'] === 'user' ? 'selected' : '' ?>>👤 Usuario</option>
                        <option value="admin" <?= $usuario['role'] === 'admin' ? 'selected' : '' ?>>🛡️ Administrador</option>
                    </select>
                </div>
                
                <div class="pt-4 border-t border-border flex gap-3">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Cambios
                    </button>
                    <a href="./admin/users" class="bg-muted hover:bg-muted/80 text-muted-foreground font-bold py-3 px-6 rounded-xl transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Nota de Privacidad -->
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden mt-6 p-6">
            <h3 class="font-bold text-foreground flex items-center mb-2">
                <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Política de Privacidad
            </h3>
            <p class="text-sm text-muted-foreground">
                De acuerdo a nuestras políticas de privacidad, los administradores no pueden modificar directamente los datos personales (nombre, email, contraseña) de los usuarios. Solo es posible gestionar sus roles dentro del sistema.
            </p>
        </div>
    </div>
    
    <!-- Panel lateral -->
    <div class="lg:col-span-1">
        <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
            <div class="bg-muted px-6 py-4 border-b border-border">
                <h3 class="text-lg font-bold text-foreground">Información</h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Avatar -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br <?= $usuario['role'] === 'admin' ? 'from-red-500 to-orange-600' : 'from-blue-500 to-cyan-600' ?> rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold">
                        <?= strtoupper(substr($usuario['name'], 0, 2)) ?>
                    </div>
                    <span class="inline-block mt-3 px-3 py-1 <?= $usuario['role'] === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500' ?> text-xs font-bold rounded-full">
                        <?= strtoupper($usuario['role']) ?>
                    </span>
                </div>
                
                <!-- Info -->
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-1">ID de Usuario</p>
                    <p class="font-mono font-bold text-foreground">#<?= $usuario['id'] ?></p>
                </div>
                
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-1">Registrado</p>
                    <p class="font-medium text-foreground"><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></p>
                </div>
                
                <?php if (isset($pedidosCount)): ?>
                <div class="p-4 bg-muted rounded-xl">
                    <p class="text-sm text-muted-foreground mb-1">Pedidos Realizados</p>
                    <p class="font-bold text-foreground"><?= $pedidosCount ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Eliminar -->
                <div class="pt-4 border-t border-border">
                    <form action="./admin/users/delete" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer y eliminará todos sus datos.');">
                        <?php csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <button type="submit" class="w-full bg-destructive/10 hover:bg-destructive/20 text-destructive font-bold py-3 px-6 rounded-xl transition flex items-center justify-center border border-destructive/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar Usuario
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
