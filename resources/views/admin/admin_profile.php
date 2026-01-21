<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-foreground">Mi Perfil</h1>
            <p class="text-muted-foreground mt-1">Administrador</p>
        </div>
        <a href="./admin/dashboard" class="bg-primary/10 text-primary hover:bg-primary/20 px-5 py-2.5 rounded-xl font-bold transition">
            Volver al Dashboard
        </a>
    </div>

    <?php if (isset($_GET['success'])) : ?>
    <div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-green-500 font-medium">Perfil actualizado correctamente</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['password_success'])) : ?>
    <div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-green-500 font-medium">Contraseña cambiada correctamente</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['password_error'])) : ?>
    <div class="bg-destructive/10 border-l-4 border-destructive p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-destructive mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <p class="text-destructive font-medium">Contraseña actual incorrecta</p>
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
                    <div class="w-24 h-24 bg-gradient-to-br from-primary to-accent rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
                <h2 class="text-xl font-bold text-foreground"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-sm text-muted-foreground mt-1"><?= htmlspecialchars($user['email']) ?></p>
                <span class="inline-block mt-3 px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                    ADMINISTRADOR
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
                <form action="./admin/profile/update" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    <?php csrf_field() ?>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Nombre Completo</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="<?= htmlspecialchars($user['name']) ?>" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Correo Electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?= htmlspecialchars($user['email']) ?>" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Avatar</label>
                        <input 
                            type="file" 
                            name="avatar" 
                            accept="image/*"
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition cursor-pointer"
                        >
                        <p class="text-xs text-muted-foreground mt-1">Soporta JPG, PNG, WEBP (Max 2MB)</p>
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-bold py-3 px-6 rounded-xl transition">
                        Actualizar Información
                    </button>
                </form>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
                <div class="bg-muted/50 px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">Cambiar Contraseña</h3>
                </div>
                <form action="./admin/profile/password" method="POST" class="p-6 space-y-5">
                    <?php csrf_field() ?>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Contraseña Actual</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">Nueva Contraseña</label>
                        <input 
                            type="password" 
                            name="new_password" 
                            class="w-full px-4 py-3 bg-input border border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-foreground"
                            required
                        >
                    </div>
                    <button type="submit" class="w-full bg-muted hover:bg-muted/80 text-muted-foreground font-bold py-3 px-6 rounded-xl transition">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
