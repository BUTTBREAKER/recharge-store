<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Mi Perfil</h1>
            <p class="text-gray-500 mt-1">Gestiona tu cuenta de SisifoStore</p>
        </div>
        <a href="/" class="bg-violet-100 text-violet-700 hover:bg-violet-200 px-5 py-2.5 rounded-xl font-bold transition">
            Volver a la Tienda
        </a>
    </div>

    <?php if (isset($_GET['success'])) : ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-green-700 font-medium">Perfil actualizado correctamente</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['password_success'])) : ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-green-700 font-medium">Contraseña cambiada correctamente</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['password_error'])) : ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <p class="text-red-700 font-medium">
                <?= $_GET['password_error'] == 'mismatch' ? 'Las contraseñas no coinciden' : 'Contraseña actual incorrecta' ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar con Avatar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                </div>
                <h2 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($user['email']) ?></p>
                <span class="inline-block mt-3 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                    USUARIO
                </span>
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Miembro desde</p>
                    <p class="font-bold text-gray-900"><?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Formularios -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Información Personal</h3>
                </div>
                <form action="/profile/update" method="POST" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nombre Completo</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="<?= htmlspecialchars($user['name']) ?>" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?= htmlspecialchars($user['email']) ?>" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition">
                        Actualizar Información
                    </button>
                </form>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Cambiar Contraseña</h3>
                </div>
                <form action="/profile/password" method="POST" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña Actual</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nueva Contraseña</label>
                        <input 
                            type="password" 
                            name="new_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                    </div>
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-xl transition">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>

            <!-- Historial de Pedidos -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Mis Pedidos Recientes</h3>
                </div>
                <div class="p-6">
                    <?php if (empty($pedidos)) : ?>
                    <div class="text-center py-8">
                        <div class="text-5xl mb-3 opacity-20">📦</div>
                        <p class="text-gray-500">No tienes pedidos aún</p>
                        <a href="/" class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-bold">
                            Explorar Productos →
                        </a>
                    </div>
                    <?php else : ?>
                    <div class="space-y-3">
                        <?php foreach ($pedidos as $pedido) : ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-bold text-gray-900"><?= $pedido['paquete'] ?></p>
                                <p class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($pedido['fecha'])) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">$<?= number_format($pedido['monto'], 2) ?></p>
                                <span class="text-xs px-2 py-1 rounded-full
                                    <?= $pedido['estado'] == 'realizada' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>
                                ">
                                    <?= ucfirst($pedido['estado']) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
