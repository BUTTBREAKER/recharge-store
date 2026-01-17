<?php
// Enhanced Hero Component - Modern landing page hero
?>

<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-card via-card to-muted mb-20 animate-fade-in border border-border/40 shadow-2xl">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 lg:grid lg:grid-cols-2 lg:gap-8 items-center min-h-[600px]">
            <!-- Left Column: Content -->
            <div class="px-6 py-16 sm:px-8 lg:px-12 lg:py-24">
                <div class="max-w-xl">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 bg-primary/10 border border-primary/20 text-primary px-4 py-2 rounded-full text-sm font-semibold mb-6 animate-pulse">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        <span>+800 Jugadores Activos</span>
                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl tracking-tight font-extrabold text-foreground leading-tight mb-6">
                        <span class="block">Domina el</span>
                        <span class="block bg-gradient-to-r from-primary via-accent to-secondary bg-clip-text text-transparent animate-gradient">
                            Campo de Batalla
                        </span>
                    </h1>
                    
                    <p class="text-lg sm:text-xl text-muted-foreground leading-relaxed mb-8">
                        La forma <span class="text-foreground font-semibold">más rápida, segura y confiable</span> de obtener tus diamantes de Mobile Legends. Pagos vía Pago Móvil y Binance.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-10">
                        <a href="#games" class="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-secondary to-amber-400 hover:opacity-90 text-secondary-foreground font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <span class="text-lg">⚡ Recargar Ahora</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#games" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-card hover:bg-muted border-2 border-border hover:border-primary/50 text-foreground font-bold rounded-2xl transition-all duration-300">
                            <span>Ver Juegos</span>
                        </a>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-10 h-10 bg-green-500/10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-muted-foreground font-medium">100% Seguro</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-muted-foreground font-medium">Entrega &lt;5min</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-10 h-10 bg-secondary/10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <span class="text-muted-foreground font-medium">+800 Clientes</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Visual -->
            <div class="relative hidden lg:flex items-center justify-center h-full py-16 px-8">
                <!-- Animated Gem Illustration -->
                <div class="relative w-full max-w-md aspect-square animate-float">
                    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-[0_0_40px_rgba(139,92,246,0.6)]">
                        <defs>
                            <linearGradient id="gemGradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#C4B5FD;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#8B5CF6;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#4C1D95;stop-opacity:1" />
                            </linearGradient>
                            <filter id="glow2">
                                <feGaussianBlur stdDeviation="5" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        
                        <!-- Main Gem Shape -->
                        <path d="M100 20 L160 60 L100 180 L40 60 Z" fill="url(#gemGradient2)" stroke="#E9D5FF" stroke-width="1" filter="url(#glow2)" class="animate-glow" />
                        <path d="M40 60 L100 80 L160 60" fill="none" stroke="#E9D5FF" stroke-width="1" opacity="0.5" />
                        <path d="M100 20 L100 80 L100 180" fill="none" stroke="#E9D5FF" stroke-width="1" opacity="0.5" />
                        
                        <!-- Floating particles -->
                        <circle cx="50" cy="100" r="3" fill="#FCD34D" class="animate-pulse">
                            <animate attributeName="cy" values="100;90;100" dur="3s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="150" cy="50" r="4" fill="#FCD34D" class="animate-pulse">
                            <animate attributeName="cy" values="50;40;50" dur="4s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="100" cy="140" r="2" fill="#FCD34D" class="animate-pulse">
                            <animate attributeName="cx" values="100;110;100" dur="5s" repeatCount="indefinite" />
                        </circle>
                    </svg>
                    
                    <!-- Rotating Glow Rings -->
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/30 to-accent/30 rounded-full blur-3xl opacity-60 -z-10 animate-spin-slow"></div>
                    <div class="absolute inset-8 bg-gradient-to-r from-accent/20 to-secondary/20 rounded-full blur-2xl opacity-50 -z-10 animate-spin-reverse"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Background Glow Effects -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-r from-primary/30 to-accent/30 rounded-full blur-3xl opacity-50 animate-pulse"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-gradient-to-r from-secondary/30 to-primary/30 rounded-full blur-3xl opacity-50 animate-pulse" style="animation-delay: 1s;"></div>
</div>

<style>
@keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

.animate-spin-slow {
    animation: spin 20s linear infinite;
}

.animate-spin-reverse {
    animation: spin 15s linear infinite reverse;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
