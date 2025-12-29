<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SisifoStore - Recargas MLBB' ?></title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN - Development Mode) -->
    <!-- Note: "cdn.tailwindcss.com should not be used in production" warning is expected. We use it for rapid MVP development. -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#FEF3C7', // Amarillo pastel
                        'primary-dark': '#FDE047',
                        secondary: '#DDD6FE', // Violeta pastel
                        'secondary-dark': '#C4B5FD',
                        dark: '#111827',
                        light: '#F9FAFB',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        body.light-mode {
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 50%, #e9d5ff 100%);
        }
        body.dark-mode {
            background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 50%, #4a2c6d 100%);
            color: #e0d5f0;
        }
        .glass-nav {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: background-color 0.3s ease;
        }
        body.light-mode .glass-nav {
            background: rgba(255, 255, 255, 0.8);
        }
        body.dark-mode .glass-nav {
            background: rgba(42, 24, 70, 0.9);
            border-bottom-color: rgba(139, 92, 246, 0.3);
        }
        body.dark-mode .bg-white {
            background-color: #2a1847 !important;
            border-color: #4a2c6d !important;
        }
        body.dark-mode .text-gray-900 {
            color: #f0e7ff !important;
        }
        body.dark-mode .text-gray-800 {
            color: #e0d5f0 !important;
        }
        body.dark-mode .text-gray-700 {
            color: #d0c0e8 !important;
        }
        body.dark-mode .text-gray-600 {
            color: #c8b8e0 !important;
        }
        body.dark-mode .text-gray-500 {
            color: #b8a7d0 !important;
        }
        body.dark-mode .text-gray-400 {
            color: #a895c0 !important;
        }
        body.dark-mode .border-gray-100 {
            border-color: #4a2c6d !important;
        }
        body.dark-mode .border-gray-200 {
            border-color: #5a3c7d !important;
        }
        body.dark-mode .bg-gray-50 {
            background-color: #1f0d3a !important;
        }
        body.dark-mode .bg-gray-100 {
            background-color: #2a1847 !important;
        }
        body.dark-mode .bg-violet-50 {
            background-color: #3d2660 !important;
        }
        body.dark-mode .bg-violet-100 {
            background-color: #4a2c6d !important;
        }
        body.dark-mode .hover\:bg-violet-50:hover {
            background-color: #3d2660 !important;
        }
        body.dark-mode .hover\:text-violet-600:hover {
            color: #a78bfa !important;
        }
        body.dark-mode .text-violet-600 {
            color: #a78bfa !important;
        }
        body.dark-mode .text-violet-700 {
            color: #8b5cf6 !important;
        }
        body.dark-mode footer {
            background-color: #1a0a2e !important;
            border-top-color: #4a2c6d !important;
        }
        /* Dark mode toggle button */
        .dark-mode-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background: #e5e7eb;
            border-radius: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }
        body.dark-mode .dark-mode-toggle {
            background: #8b5cf6;
        }
        .dark-mode-toggle-circle {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s;
            display: flex;
            align-items: center;
            justify-center;
        }
        body.dark-mode .dark-mode-toggle-circle {
            transform: translateX(30px);
            background: #1a0a2e;
        }
    </style>
</head>
<body class="light-mode bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location='/'">
                    <span class="text-3xl animate-float">💎</span>
                    <h1 class="text-xl font-bold tracking-tight text-gray-900">
                        Sisifo<span class="text-violet-500">Store</span>
                    </h1>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="/" class="text-gray-600 hover:text-violet-600 font-medium transition">Inicio</a>
                    <a href="/#games" class="text-gray-600 hover:text-violet-600 font-medium transition">Juegos</a>
                    
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['user_id'])) :
                        // Calcular notificaciones
                        $pedidoModel = new Pedido();
                        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                            $notificationCount = $pedidoModel->contarPorEstado('pendiente');
                            $notificationLink = '/admin/orders?estado=pendiente';
                        } else {
                            $notificationCount = $pedidoModel->contarActivosPorUsuario($_SESSION['user_id']);
                            $notificationLink = '/notifications';
                        }
                        ?>
                        <!-- Notification Bell -->
                        <a href="<?= $notificationLink ?>" class="relative text-gray-600 hover:text-violet-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <?php if ($notificationCount > 0) : ?>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    <?= $notificationCount > 9 ? '9+' : $notificationCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                            <a href="/admin/dashboard" class="text-violet-600 font-bold hover:text-violet-800 transition">Panel Admin</a>
                        <?php endif; ?>
                        
                        <div class="relative group">
                            <button class="flex items-center text-gray-700 hover:text-violet-600 font-medium transition">
                                <span class="mr-2">Hola, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute right-0 w-48 bg-white rounded-xl shadow-lg py-2 mt-2 hidden group-hover:block border border-gray-100 animate-fade-in z-50">
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                                    <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-50">Mi Perfil</a>
                                <?php else : ?>
                                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-violet-50">Mi Perfil</a>
                                <?php endif; ?>
                                <a href="/logout" class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="/login" class="text-gray-500 hover:text-violet-600 text-sm font-medium transition flex items-center">
                            Iniciar Sesión
                        </a>
                        <a href="/register" class="bg-violet-100 text-violet-700 px-4 py-2 rounded-full font-semibold hover:bg-violet-200 transition shadow-sm text-sm">
                            Unirse
                        </a>
                    <?php endif; ?>
                    
                    <!-- Dark Mode Toggle -->
                    <div class="dark-mode-toggle" onclick="toggleDarkMode()" title="Cambiar tema">
                        <div class="dark-mode-toggle-circle">
                            <span class="text-xs">🌙</span>
                        </div>
                    </div>
                    
                    <a href="/#games" class="bg-gray-900 text-white px-4 py-2 rounded-full font-bold hover:bg-black transition shadow-lg flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Recargar
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full z-50 shadow-xl">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-violet-600 hover:bg-violet-50">Inicio</a>
                <a href="/#games" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-violet-600 hover:bg-violet-50">Juegos</a>
                
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                        <a href="/admin/dashboard" class="block px-3 py-2 rounded-md text-base font-bold text-violet-600 bg-violet-50">Panel Admin</a>
                    <?php endif; ?>
                    <div class="border-t border-gray-100 my-2 pt-2">
                        <p class="px-3 text-sm text-gray-400 mb-2">Cuenta</p>
                        <span class="block px-3 py-2 text-gray-800 font-bold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
                        
                        <!-- Notifications -->
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                            <a href="/admin/orders?estado=pendiente" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50 flex items-center justify-between">
                                <span>Recargas Pendientes</span>
                                <?php if ($notificationCount > 0) : ?>
                                    <span class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                        <?= $notificationCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php else : ?>
                            <a href="/notifications" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50 flex items-center justify-between">
                                <span>Mis Notificaciones</span>
                                <?php if ($notificationCount > 0) : ?>
                                    <span class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                        <?= $notificationCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                            <a href="/admin/profile" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50">Mi Perfil</a>
                        <?php else : ?>
                            <a href="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-violet-50">Mi Perfil</a>
                        <?php endif; ?>
                        <a href="/logout" class="block px-3 py-2 rounded-md text-base font-medium text-red-500 hover:bg-red-50">Cerrar Sesión</a>
                    </div>
                <?php else : ?>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <a href="/login" class="text-center py-3 border border-gray-200 rounded-xl font-bold text-gray-600">Entrar</a>
                        <a href="/register" class="text-center py-3 bg-violet-600 text-white rounded-xl font-bold shadow-lg shadow-violet-200">Unirse</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?= $content ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:justify-between">
                <div class="mb-8 md:mb-0">
                     <h2 class="text-lg font-bold text-gray-900">SisifoStore</h2>
                     <p class="mt-2 text-sm text-gray-500">Recargas seguras y rápidas para Mobile Legends.</p>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Legal</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="/legal" class="text-base text-gray-500 hover:text-gray-900">Aviso Legal</a></li>
                            <li><a href="/reembolsos" class="text-base text-gray-500 hover:text-gray-900">Reembolsos</a></li>
                        </ul>
                    </div>
                </div>
            </div>
             <div class="mt-8 border-t border-gray-200 pt-8 text-center md:text-left">
                <p class="text-sm text-gray-400">&copy; <?= date('Y') ?> SisifoStore. Todos los derechos reservados.</p>
                <p class="text-xs text-gray-300 mt-2">Este sitio no está afiliado a Moonton. Mobile Legends: Bang Bang es marca registrada de Moonton.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // Dark mode toggle
        function toggleDarkMode() {
            const body = document.body;
            const isDark = body.classList.contains('dark-mode');
            
            if (isDark) {
                body.classList.remove('dark-mode');
                body.classList.add('light-mode');
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.remove('light-mode');
                document.body.classList.add('dark-mode');
            }
        });
    </script>
</body>
</html>
