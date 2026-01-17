<?php
// Hero Component - v0 style with dark mode support
?>

<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-card to-muted mb-16 animate-fade-in border border-border/40">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-foreground sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Domina el campo de batalla</span>
                        <span class="block bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">Recarga Diamantes al instante</span>
                    </h1>
                    <p class="mt-3 text-base text-muted-foreground sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        La forma más rápida, segura y confiable de obtener tus diamantes de Mobile Legends. Pagos vía Pago Móvil y Binance.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <a href="#games" class="w-full flex items-center justify-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-secondary-foreground bg-gradient-to-r from-secondary to-amber-400 hover:opacity-90 md:py-4 md:text-lg md:px-10 transition-all duration-300">
                                ⚡ Recargar Ahora
                            </a>
                        </div>
                    </div>
                    
                    <!-- Trust badges -->
                    <div class="mt-8 flex items-center gap-6 sm:justify-center lg:justify-start">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>100% Seguro</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                            </svg>
                            <span>Entrega Rápida</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>+<?= rand(1100, 1500) ?> Clientes</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span><?= rand(95, 99) ?>% Éxito</span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Right side decorative element -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 flex items-center justify-center pointer-events-none">
        <!-- Abstract Gem Illustration -->
        <div class="relative w-96 h-96 animate-float">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-[0_0_30px_rgba(139,92,246,0.5)]">
                <defs>
                    <linearGradient id="gemGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#C4B5FD;stop-opacity:1" />
                        <stop offset="50%" style="stop-color:#8B5CF6;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#4C1D95;stop-opacity:1" />
                    </linearGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="5" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                
                <!-- Main Gem Shape -->
                <path d="M100 20 L160 60 L100 180 L40 60 Z" fill="url(#gemGradient)" stroke="#E9D5FF" stroke-width="1" filter="url(#glow)" />
                <path d="M40 60 L100 80 L160 60" fill="none" stroke="#E9D5FF" stroke-width="1" opacity="0.5" />
                <path d="M100 20 L100 80 L100 180" fill="none" stroke="#E9D5FF" stroke-width="1" opacity="0.5" />
                
                <!-- Floating particles -->
                <circle cx="50" cy="100" r="2" fill="#FCD34D" class="animate-pulse">
                    <animate attributeName="cy" values="100;90;100" dur="3s" repeatCount="indefinite" />
                </circle>
                <circle cx="150" cy="50" r="3" fill="#FCD34D" class="animate-pulse">
                    <animate attributeName="cy" values="50;40;50" dur="4s" repeatCount="indefinite" />
                </circle>
            </svg>
            
            <!-- Glow background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/40 to-accent/40 rounded-full blur-3xl opacity-40 -z-10"></div>
        </div>
    </div>
    
    <!-- Glow effect -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-r from-primary/30 to-accent/30 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-gradient-to-r from-secondary/30 to-primary/30 rounded-full blur-3xl opacity-50"></div>
</div>
