<div class="max-w-md mx-auto mt-20 animate-fade-in">
    <div class="bg-white p-10 rounded-3xl shadow-2xl border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>
        <div class="text-center mb-8">
            <span class="text-4xl">🔐</span>
            <h1 class="text-2xl font-bold mt-4 text-gray-900">Panel Administrativo</h1>
            <p class="text-gray-500 text-sm">Ingresa tus credenciales para continuar</p>
        </div>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm text-center border border-red-100 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Usuario o contraseña incorrectos
            </div>
        <?php endif; ?>

        <form action="/admin/login" method="POST" class="space-y-6">
            <?php csrf_field() ?>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Usuario</label>
                <div class="relative">
                    <input type="text" name="usuario" required placeholder="admin" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-500 transition-all">
                    <div class="absolute left-4 top-[18px] text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-500 transition-all">
                    <div class="absolute left-4 top-[18px] text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                Entrar al Panel
            </button>
        </form>
    </div>
</div>
