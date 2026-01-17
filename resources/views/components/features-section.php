<?php
// Features Section - Modern benefits showcase
?>

<section class="py-20 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-30">
        <svg width="100%" height="100%">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="currentColor" class="text-primary/20"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-4xl md:text-5xl font-extrabold text-foreground mb-4">
                ¿Por qué elegirnos?
            </h2>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                Experiencia premium en recargas digitales con las mejores garantías del mercado
            </p>
        </div>

        <!-- Bento Grid Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1: Instant Delivery -->
            <div class="group relative bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 hover:shadow-2xl hover:shadow-primary/20 hover:border-primary/50 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-accent rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-primary/30">
                        <img src="/assets/icons/zap.svg" alt="Lightning" class="w-8 h-8 text-primary-foreground" style="filter: brightness(0) invert(1);">
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-3">
                        Entrega Inmediata
                    </h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Recibe tus diamantes en menos de 5 minutos. Proceso 100% automatizado y seguro.
                    </p>
                </div>
            </div>

            <!-- Feature 2: Secure Payments -->
            <div class="group relative bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 hover:shadow-2xl hover:shadow-green-500/20 hover:border-green-500/50 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-green-500/30">
                        <img src="/assets/icons/shield-check.svg" alt="Shield" class="w-8 h-8" style="filter: brightness(0) invert(1);">
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-3">
                        Pagos Seguros
                    </h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Procesamiento 100% seguro con Binance Pay y Pago Móvil. Tus datos siempre protegidos.
                    </p>
                </div>
            </div>

            <!-- Feature 3: 24/7 Support -->
            <div class="group relative bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 hover:shadow-2xl hover:shadow-blue-500/20 hover:border-blue-500/50 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/30">
                        <img src="/assets/icons/headset.svg" alt="Headset" class="w-8 h-8" style="filter: brightness(0) invert(1);">
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-3">
                        Soporte 24/7
                    </h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Estamos aquí para ayudarte en cualquier momento. Atención rápida y personalizada.
                    </p>
                </div>
            </div>

            <!-- Feature 4: Multiple Methods -->
            <div class="group relative bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 hover:shadow-2xl hover:shadow-amber-500/20 hover:border-amber-500/50 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-amber-500/30">
                        <img src="/assets/icons/wallet-cards.svg" alt="Wallet" class="w-8 h-8" style="filter: brightness(0) invert(1);">
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-3">
                        Múltiples Métodos
                    </h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Binance Pay y Pago Móvil disponibles. Elige el método que mejor te convenga.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
