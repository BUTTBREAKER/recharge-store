<div class="max-w-md mx-auto mt-20 animate-fade-in relative z-10">
    <div class="bg-card p-10 rounded-3xl shadow-2xl border border-border relative overflow-hidden backdrop-blur-sm">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-accent"></div>
        <div class="text-center mb-8">
            <span class="text-4xl text-foreground">🔐</span>
            <h1 class="text-2xl font-bold mt-4 text-foreground">Iniciar Sesión</h1>
            <p class="text-muted-foreground text-sm">Bienvenido a FearSold</p>
        </div>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="bg-destructive/10 text-destructive p-4 rounded-xl mb-6 text-sm text-center border border-destructive/20 flex items-center justify-center animate-shake">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Credenciales incorrectas
            </div>
        <?php endif; ?>

        <form action="./login" method="POST" class="space-y-6">
            <?php csrf_field() ?>
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Correo Electrónico</label>
                <div class="relative">
                    <input type="email" name="email" required placeholder="tu@email.com" class="w-full p-4 pl-12 bg-input border border-border rounded-xl outline-none focus:ring-2 focus:ring-ring focus:border-input text-foreground transition-all font-medium placeholder:text-muted-foreground">
                    <div class="absolute left-4 top-[18px] text-muted-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full p-4 pl-12 bg-input border border-border rounded-xl outline-none focus:ring-2 focus:ring-ring focus:border-input text-foreground transition-all font-medium placeholder:text-muted-foreground">
                    <div class="absolute left-4 top-[18px] text-muted-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <a href="./forgot-password" class="text-sm text-primary hover:underline font-semibold">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent text-primary-foreground font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                Ingresar
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-border text-center">
            <p class="text-muted-foreground text-sm">¿No tienes cuenta?</p>
            <a href="./register" class="text-primary font-bold hover:underline mt-1 inline-block">Únete a FearSold</a>
        </div>
    </div>
</div>
