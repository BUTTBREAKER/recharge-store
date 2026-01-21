<div class="max-w-md mx-auto mt-20 animate-fade-in relative z-10">
    <div class="bg-card p-10 rounded-3xl shadow-2xl border border-border relative overflow-hidden backdrop-blur-sm">
        <div class="text-center mb-8">
            <span class="text-4xl text-foreground">✨</span>
            <h1 class="text-2xl font-bold mt-4 text-foreground">Nueva Contraseña</h1>
            <p class="text-muted-foreground text-sm">Establece tu nueva clave de acceso</p>
        </div>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="bg-destructive/10 text-destructive p-4 rounded-xl mb-6 text-sm text-center border border-destructive/20 animate-shake">
                <?= $_GET['error'] == 'mismatch' ? 'Las contraseñas no coinciden' : 'Enlace inválido o expirado' ?>
            </div>
        <?php endif; ?>

        <form action="./reset-password" method="POST" class="space-y-6">
            <?php csrf_field() ?>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Nueva Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full p-4 pl-12 bg-input border border-border rounded-xl outline-none focus:ring-2 focus:ring-primary focus:border-input text-foreground transition-all font-medium placeholder:text-muted-foreground">
                    <div class="absolute left-4 top-[18px] text-muted-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
            </div>
             <div>
                <label class="block text-sm font-bold text-foreground mb-2">Confirmar Contraseña</label>
                <div class="relative">
                    <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full p-4 pl-12 bg-input border border-border rounded-xl outline-none focus:ring-2 focus:ring-primary focus:border-input text-foreground transition-all font-medium placeholder:text-muted-foreground">
                    <div class="absolute left-4 top-[18px] text-muted-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent text-primary-foreground font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                Restablecer Contraseña
            </button>
        </form>
    </div>
</div>
