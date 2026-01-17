<?php
// Final CTA Section
?>

<section class="py-20 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary via-accent to-secondary opacity-10"></div>
    <div class="absolute inset-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-accent/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-card/80 backdrop-blur-md border border-border rounded-3xl p-12 md:p-16 shadow-2xl">
                <!-- Icon -->
                <div class="w-20 h-20 bg-gradient-to-br from-primary to-accent rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg shadow-primary/50 animate-float">
                    <svg class="w-10 h-10 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>

                <h2 class="text-4xl md:text-5xl font-extrabold text-foreground mb-6">
                    ¿Listo para recargar?
                </h2>
                <p class="text-xl text-muted-foreground mb-10 max-w-2xl mx-auto leading-relaxed">
                    Únete a cientos de jugadores que confían en nosotros. Empieza ahora y recibe tus diamantes al instante.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="#games" class="group w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-primary to-accent text-primary-foreground font-bold rounded-2xl hover:opacity-90 transition-all duration-300 shadow-xl hover:shadow-2xl hover:shadow-primary/50 flex items-center justify-center gap-2 transform hover:-translate-y-1">
                        <span class="text-lg">Recargar Ahora</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>

                    <a href="./soporte" class="w-full sm:w-auto px-10 py-5 bg-muted hover:bg-muted/80 text-foreground font-bold rounded-2xl transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>¿Necesitas Ayuda?</span>
                    </a>
                </div>

                <!-- Trust Line -->
                <div class="mt-8 flex items-center justify-center gap-2 text-sm text-muted-foreground">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Pago seguro • Sin cargos ocultos • Garantía de devolución</span>
                </div>
            </div>
        </div>
    </div>
</section>
