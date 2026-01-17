<?php

use Leaf\Http\Session;

?>
<!doctype html>
<html
    lang="es"
    class="scroll-smooth"
    x-data='{
        isDark: localStorage.getItem("theme") === "dark" || (!localStorage.getItem("theme") && window.matchMedia("(prefers-color-scheme: dark)").matches),
        sidebarOpen: false,
        init() {
            this.updateTheme();
        },
        updateTheme() {
            if (this.isDark) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        },
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem("theme", this.isDark ? "dark" : "light");
            this.updateTheme();
        }
    }'
    x-init="init()"
    :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?? 'FearSold Admin' ?></title>
    <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
    <link rel="icon" href="./images/favicon.svg" />

    <!-- Google Fonts: Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="./index.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="bg-background text-foreground transition-colors duration-300 font-sans antialiased overflow-hidden">

    <div class="flex h-screen">
        <!-- Sidebar Desktop -->
        <aside class="w-64 bg-card border-r border-border hidden md:flex flex-col flex-shrink-0 transition-all duration-300">
            <div class="p-6 border-b border-border flex items-center justify-center">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:shadow-primary/50 transition-all">
                        F
                    </div>
                    <span class="text-xl font-extrabold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">FearSold</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <div class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2 px-2 mt-2">Principal</div>
                
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <div class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2 px-2 mt-6">Gestión</div>

                <a href="/admin/orders" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Pedidos
                </a>

                <a href="/admin/prices" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/prices') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Productos
                </a>

                <a href="/admin/games" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/games') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    Juegos
                </a>

                <a href="/admin/users" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Usuarios
                </a>

                <div class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2 px-2 mt-6">Sistema</div>

                <a href="/admin/payments" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/payments') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Métodos de Pago
                </a>

                <a href="/admin/config" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors hover:bg-primary/10 hover:text-primary <?= strpos($_SERVER['REQUEST_URI'], '/admin/config') !== false ? 'bg-primary/10 text-primary' : 'text-muted-foreground' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Configuración
                </a>
            </nav>

            <div class="p-4 border-t border-border mt-auto">
                <a href="/admin/profile" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted transition-colors mb-2">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                        <?= strtoupper(substr(Session::get('user_name') ?? 'Admin', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-foreground"><?= Session::get('user_name') ?></div>
                        <div class="text-xs text-muted-foreground">Admin</div>
                    </div>
                </a>
                <a href="/logout" class="flex items-center justify-center gap-2 p-2 rounded-lg text-destructive hover:bg-destructive/10 transition-colors w-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-background">
            
            <!-- Mobile Header -->
            <header class="md:hidden flex items-center justify-between p-4 bg-card border-b border-border">
                <a href="/" class="text-lg font-bold text-foreground">FearSold Admin</a>
                <button @click="sidebarOpen = !sidebarOpen" class="text-muted-foreground">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-50 bg-black/50 md:hidden" @click="sidebarOpen = false" x-transition.opacity></div>
            
            <!-- Mobile Sidebar -->
            <aside x-show="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-64 bg-card border-r border-border md:hidden flex flex-col transition-transform duration-300 transform" 
                x-transition:enter="translate-x-full" x-transition:leave="-translate-x-full">
                <!-- Same content as desktop sidebar, simplified for brevity -->
                <div class="p-6 border-b border-border flex justify-between items-center">
                    <span class="text-xl font-bold text-foreground">Menú</span>
                    <button @click="sidebarOpen = false" class="text-muted-foreground">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                 <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                    <a href="/admin/dashboard" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Dashboard</a>
                    <a href="/admin/orders" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Pedidos</a>
                    <a href="/admin/prices" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Productos</a>
                    <a href="/admin/games" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Juegos</a>
                    <a href="/admin/users" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Usuarios</a>
                    <a href="/admin/payments" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Pagos</a>
                    <a href="/admin/config" class="block px-4 py-2 text-foreground hover:bg-muted rounded-lg">Configuración</a>
                </nav>
                 <div class="p-4 border-t border-border mt-auto">
                    <a href="/logout" class="block w-full text-center p-2 rounded-lg bg-destructive/10 text-destructive font-bold">Salir</a>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth relative">
                <!-- Top Bar for Desktop -->
                 <div class="hidden md:flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-foreground"><?= $title ?? 'Admin' ?></h1>
                    
                    <div class="flex items-center gap-4">
                        <button @click="toggleTheme()" class="p-2 rounded-full bg-muted text-muted-foreground hover:bg-muted/80 transition-colors">
                            <span x-show="!isDark">🌙</span>
                            <span x-show="isDark">☀️</span>
                        </button>
                    </div>
                </div>

                <?= $content ?>
            </main>
        </div>
    </div>
</body>
</html>
