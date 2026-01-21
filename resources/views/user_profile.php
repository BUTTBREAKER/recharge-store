<div class="max-w-4xl mx-auto animate-fade-in relative z-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-foreground">Mi Perfil</h1>
            <p class="text-muted-foreground mt-1">Gestiona tu cuenta de FearSold</p>
        </div>
        <a href="./" class="bg-primary/10 text-primary hover:bg-primary/20 px-5 py-2.5 rounded-xl font-bold transition">
            Volver a la Tienda
        </a>
    </div>

    <?php if (isset($_GET['success'])) : ?>
    <div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-green-600 font-medium">Perfil actualizado correctamente</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['password_success'])) : ?>
    <div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-green-600 font-medium">Contraseña cambiada correctamente</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['password_error'])) : ?>
    <div class="bg-destructive/10 border-l-4 border-destructive p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-destructive mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <p class="text-destructive font-medium">
                <?= $_GET['password_error'] == 'mismatch' ? 'Las contraseñas no coinciden' : 'Contraseña actual incorrecta' ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar con Avatar -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-2xl shadow-lg border border-border p-6 text-center">
                <?php if (!empty($user['avatar_url'])) : ?>
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-primary/20 shadow-lg">
                        <img src="./<?= $user['avatar_url'] ?>" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                <?php else : ?>
                    <div class="w-24 h-24 bg-gradient-to-br from-primary to-accent rounded-full mx-auto mb-4 flex items-center justify-center text-primary-foreground text-3xl font-bold shadow-lg">
                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
                <h2 class="text-xl font-bold text-foreground"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-sm text-muted-foreground mt-1"><?= htmlspecialchars($user['email']) ?></p>
                <span class="inline-block mt-3 px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full border border-primary/20">
                    USUARIO
                </span>
                <div class="mt-6 pt-6 border-t border-border">
                    <p class="text-xs text-muted-foreground">Miembro desde</p>
                    <p class="font-bold text-foreground"><?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Formularios -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
                <div class="bg-muted/50 px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Información Personal</h3>
                </div>
                <form action="./profile/update" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    <?php csrf_field() ?>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Nombre Completo</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="<?= htmlspecialchars($user['name']) ?>" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-shadow"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Correo Electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?= htmlspecialchars($user['email']) ?>" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-shadow"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Avatar</label>
                        <input 
                            type="file" 
                            name="avatar" 
                            accept="image/*"
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition cursor-pointer"
                        >
                        <p class="text-xs text-muted-foreground mt-1">Soporta JPG, PNG, WEBP (Max 2MB)</p>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent hover:opacity-90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition shadow-lg">
                        Actualizar Información
                    </button>
                </form>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
                <div class="bg-muted/50 px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Cambiar Contraseña</h3>
                </div>
                <form action="./profile/password" method="POST" class="p-6 space-y-5">
                    <?php csrf_field() ?>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Contraseña Actual</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-shadow"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Nueva Contraseña</label>
                        <input 
                            type="password" 
                            name="new_password" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-shadow"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Confirmar Nueva Contraseña</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-shadow"
                            required
                        >
                    </div>
                    <button type="submit" class="w-full bg-secondary hover:bg-secondary/80 text-secondary-foreground font-bold py-3 px-6 rounded-xl transition shadow-lg">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>

            <!-- Historial de Pedidos -->
            <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
                <div class="bg-muted/50 px-6 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="text-lg font-bold text-foreground">Mis Pedidos Recientes</h3>
                    <a href="./profile/orders" class="text-sm text-primary hover:text-primary/80 font-bold">
                        Ver Todos →
                    </a>
                </div>
                <div class="p-6">
                    <?php if (empty($pedidos)) : ?>
                    <div class="text-center py-8">
                        <div class="text-5xl mb-3 opacity-20 filter grayscale">📦</div>
                        <p class="text-muted-foreground">No tienes pedidos aún</p>
                        <a href="./" class="inline-block mt-4 text-primary hover:underline font-bold">
                            Explorar Productos →
                        </a>
                    </div>
                    <?php else : ?>
                    <div class="space-y-3">
                        <?php foreach ($pedidos as $pedido) : ?>
                        <div class="flex items-center justify-between p-4 bg-muted/30 border border-border/50 rounded-xl hover:bg-muted/50 transition-colors">
                            <div>
                                <p class="font-bold text-foreground"><?= $pedido['paquete'] ?></p>
                                <p class="text-sm text-muted-foreground"><?= date('d/m/Y', strtotime($pedido['fecha'])) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-foreground">$<?= number_format($pedido['monto'], 2) ?></p>
                                <span class="text-xs px-2 py-1 rounded-full
                                    <?= $pedido['estado'] == 'realizada' ? 'bg-green-500/10 text-green-600 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-600 border border-yellow-500/20' ?>
                                ">
                                    <?= ucfirst($pedido['estado']) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="./profile/orders" class="block mt-4 text-center bg-primary/10 text-primary font-bold py-3 px-6 rounded-xl hover:bg-primary/20 transition-colors border border-primary/20">
                        Ver Historial Completo
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
