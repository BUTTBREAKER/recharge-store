<div class="max-w-md mx-auto mt-20 animate-fade-in relative z-10">
    <div class="bg-card p-10 rounded-3xl shadow-2xl border border-border relative overflow-hidden backdrop-blur-sm">
        <div class="text-center mb-8">
            <span class="text-4xl text-foreground">🔐</span>
            <h1 class="text-2xl font-bold mt-4 text-foreground">Recuperar Acceso</h1>
            <p class="text-muted-foreground text-sm">Ingresa tu correo para restablecer tu contraseña</p>
        </div>
        
        <?php if (isset($_GET['success'])) : ?>
            <div class="bg-green-500/10 text-green-600 p-4 rounded-xl mb-6 text-sm text-center border border-green-500/20">
                <p>Te hemos enviado un enlace de recuperación.</p>
                <?php if (\Leaf\Http\Session::has('demo_reset_link')): ?>
                    <div class="mt-2 p-2 bg-black/10 rounded text-xs break-all cursor-text select-all">
                        DEMO LINK: <a href="<?= \Leaf\Http\Session::get('demo_reset_link') ?>" class="underline">Click Aquí</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form action="./forgot-password" method="POST" class="space-y-6">
            <?php csrf_field() ?>
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Correo Electrónico</label>
                <div class="relative">
                    <input type="email" name="email" required placeholder="tu@email.com" class="w-full p-4 pl-12 bg-input border border-border rounded-xl outline-none focus:ring-2 focus:ring-primary focus:border-input text-foreground transition-all font-medium placeholder:text-muted-foreground">
                    <div class="absolute left-4 top-[18px] text-muted-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent text-primary-foreground font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                Enviar Enlace
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-border text-center">
            <a href="./login" class="text-primary font-bold hover:underline inline-block">Volver al Login</a>
        </div>
    </div>
</div>
