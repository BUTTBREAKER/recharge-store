<?php
// 404 Error Page
?>
<div class="min-h-[80vh] flex flex-col items-center justify-center text-center px-4 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-background to-accent/5 -z-10"></div>
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-accent/20 rounded-full blur-[100px] animate-pulse delay-1000"></div>

    <!-- 404 Content -->
    <h1 class="text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent mb-4 animate-float">
        404
    </h1>
    
    <div class="space-y-6 max-w-md mx-auto relative z-10">
        <h2 class="text-3xl font-bold text-foreground">¡Página no encontrada!</h2>
        <p class="text-muted-foreground text-lg">
            Parece que te has perdido en la jungla. La página que buscas no existe o ha sido movida.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="./" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-primary to-accent text-primary-foreground font-bold hover:opacity-90 transition-all hover:scale-105 shadow-lg shadow-primary/25 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Volver al Inicio
            </a>
            <a href="./soporte" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-muted text-foreground font-bold hover:bg-muted/80 transition-all hover:scale-105 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Ayuda / Soporte
            </a>
        </div>
    </div>
</div>
