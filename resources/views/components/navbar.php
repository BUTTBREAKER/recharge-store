<?php
// Navbar Component - v0 style with dark mode support
use Leaf\Http\Session;
?>

<nav class="sticky top-0 z-50 w-full border-b border-border/40 bg-card/95 backdrop-blur supports-[backdrop-filter]:bg-card/60">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <a href="./" class="flex items-center space-x-2 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary to-accent rounded-lg blur-sm opacity-50 group-hover:opacity-75 transition-opacity"></div>
                    <div class="relative bg-gradient-to-r from-primary to-accent p-2 rounded-lg">
                        <svg class="h-6 w-6 text-primary-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                    Fear Sold
                </span>
            </a>

            <!-- Desktop Navigation - Simplified -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="./" class="text-sm font-semibold text-foreground/80 hover:text-foreground transition-colors relative group">
                    Inicio
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-accent group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="./#games" class="text-sm font-semibold text-foreground/80 hover:text-foreground transition-colors relative group">
                    Juegos
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-accent group-hover:w-full transition-all duration-300"></span>
                </a>
                <?php if (Session::get('user_role') === 'admin'): ?>
                <a href="./admin/dashboard" class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                    Panel Admin
                </a>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2">
                <!-- Dark Mode Toggle (Standardized with Admin) -->
                <button 
                    @click="toggleTheme()"
                    class="p-2 rounded-full bg-muted text-muted-foreground hover:bg-muted/80 transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary/20"
                    aria-label="Toggle dark mode"
                >
                    <span x-show="!isDark" class="text-xl leading-none">🌙</span>
                    <span x-show="isDark" class="text-xl leading-none">☀️</span>
                </button>

                <!-- Notifications -->
                <?php if (Session::has('user_id')): ?>
                <a href="./notifications" class="relative p-2 rounded-lg hover:bg-muted transition-all duration-300 text-muted-foreground hover:text-primary">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </a>
                <?php endif; ?>

                <!-- User Menu -->
                <?php if (Session::has('user_id')): ?>
                <div x-data="{ open: false }" class="relative">
                    <button 
                        @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-muted transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-ring"
                    >
                        <div class="w-8 h-8 bg-gradient-to-br from-primary to-accent rounded-full flex items-center justify-center text-primary-foreground font-bold text-sm">
                            <?= strtoupper(substr(Session::get('user_name') ?? 'U', 0, 1)) ?>
                        </div>
                    </button>
                    
                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-card/95 backdrop-blur-sm border border-border rounded-2xl shadow-xl py-2 z-50"
                    >
                        <div class="px-4 py-3 border-b border-border">
                            <p class="text-sm font-semibold text-foreground"><?= Session::get('user_name') ?></p>
                            <p class="text-xs text-muted-foreground">Jugador</p>
                        </div>
                        <a href="./profile" class="block px-4 py-2.5 text-sm text-foreground hover:bg-muted transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Mi Perfil
                        </a>
                        <a href="./profile/orders" class="block px-4 py-2.5 text-sm text-foreground hover:bg-muted transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Mis Pedidos
                        </a>
                        <hr class="my-2 border-border">
                        <a href="./logout" class="block px-4 py-2.5 text-sm text-destructive hover:bg-destructive/10 transition-colors flex items-center gap-2 rounded-lg mx-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="./login" class="hidden sm:inline-flex px-5 py-2.5 text-sm font-semibold text-foreground hover:bg-muted rounded-xl transition-colors">
                    Iniciar Sesión
                </a>
                <a href="./register" class="px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-primary to-accent text-primary-foreground rounded-xl hover:opacity-90 transition-opacity shadow-lg hover:shadow-xl">
                    Registrarse
                </a>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 rounded-lg hover:bg-muted transition-colors"
                >
                    <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="md:hidden py-4 space-y-2 border-t border-border/40"
        >
            <a href="./" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-foreground transition-colors">
                Inicio
            </a>
            <a href="./#games" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-foreground transition-colors">
                Juegos
            </a>
            <?php if (Session::has('user_id')): ?>
            <a href="./profile" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-foreground transition-colors">
                Mi Perfil
            </a>
            <a href="./profile/orders" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-foreground transition-colors">
                Mis Pedidos
            </a>
            <?php else: ?>
            <a href="./login" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-foreground/80 hover:bg-muted hover:text-foreground transition-colors">
                Iniciar Sesión
            </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
