<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900">Configuración de Pagos</h1>
        <p class="text-gray-500 mt-1">Gestiona los datos de los métodos de pago</p>
    </div>
    <a href="/admin/dashboard" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al Dashboard
    </a>
</div>

<?php if(isset($_GET['success'])): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-700 font-medium">Configuración actualizada correctamente</p>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pago Móvil -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-5">
            <div class="flex items-center text-white">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <div>
                    <h2 class="text-xl font-bold">Pago Móvil</h2>
                    <p class="text-blue-100 text-sm">Datos bancarios Venezuela</p>
                </div>
            </div>
        </div>
        <form action="/admin/payments/update" method="POST" class="p-6 space-y-5">
            <input type="hidden" name="metodo" value="pagomovil">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Banco</label>
                <select name="banco" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Banco de Venezuela" <?= isset($pagoMovil['config_data']['banco']) && $pagoMovil['config_data']['banco'] == 'Banco de Venezuela' ? 'selected' : '' ?>>Banco de Venezuela</option>
                    <option value="Banesco" <?= isset($pagoMovil['config_data']['banco']) && $pagoMovil['config_data']['banco'] == 'Banesco' ? 'selected' : '' ?>>Banesco</option>
                    <option value="Mercantil" <?= isset($pagoMovil['config_data']['banco']) && $pagoMovil['config_data']['banco'] == 'Mercantil' ? 'selected' : '' ?>>Mercantil</option>
                    <option value="Provincial" <?= isset($pagoMovil['config_data']['banco']) && $pagoMovil['config_data']['banco'] == 'Provincial' ? 'selected' : '' ?>>Provincial</option>
                    <option value="Bancaribe" <?= isset($pagoMovil['config_data']['banco']) && $pagoMovil['config_data']['banco'] == 'Bancaribe' ? 'selected' : '' ?>>Bancaribe</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Teléfono</label>
                <input 
                    type="text" 
                    name="telefono" 
                    value="<?= $pagoMovil['config_data']['telefono'] ?? '' ?>" 
                    placeholder="0424-1234567"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Cédula</label>
                <input 
                    type="text" 
                    name="cedula" 
                    value="<?= $pagoMovil['config_data']['cedula'] ?? '' ?>" 
                    placeholder="V-12345678"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Titular</label>
                <input 
                    type="text" 
                    name="titular" 
                    value="<?= $pagoMovil['config_data']['titular'] ?? '' ?>" 
                    placeholder="John Doe"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition">
                Guardar Configuración Pago Móvil
            </button>
        </form>
    </div>

    <!-- Binance Pay -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-amber-500 to-yellow-600 px-6 py-5">
            <div class="flex items-center text-white">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h2 class="text-xl font-bold">Binance Pay</h2>
                    <p class="text-amber-100 text-sm">Pagos en criptomonedas</p>
                </div>
            </div>
        </div>
        <form action="/admin/payments/update" method="POST" class="p-6 space-y-5">
            <input type="hidden" name="metodo" value="binance">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Merchant ID</label>
                <input 
                    type="text" 
                    name="merchant_id" 
                    value="<?= $binance['config_data']['merchant_id'] ?? '' ?>" 
                    placeholder="Tu Merchant ID de Binance"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                >
                <p class="text-xs text-gray-500 mt-1">Obtén tu Merchant ID desde el panel de Binance Pay</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">API Key (Opcional)</label>
                <input 
                    type="text" 
                    name="api_key" 
                    value="<?= $binance['config_data']['api_key'] ?? '' ?>" 
                    placeholder="API Key para integración automática"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                >
                <p class="text-xs text-gray-500 mt-1">Solo necesario para integración automática</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Instrucciones para el Cliente</label>
                <textarea 
                    name="instrucciones" 
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    placeholder="Instrucciones que verá el cliente al pagar con Binance..."
                ><?= $binance['config_data']['instrucciones'] ?? '' ?></textarea>
            </div>

            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-xl transition">
                Guardar Configuración Binance
            </button>
        </form>
    </div>
</div>

<!-- Información de Seguridad -->
<div class="mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <div class="text-sm text-red-700">
            <p class="font-bold mb-1">Seguridad de Datos de Pago</p>
            <ul class="list-disc list-inside space-y-1 text-red-600">
                <li>Nunca compartas tus claves privadas o API secrets completos</li>
                <li>Verifica que los datos bancarios sean correctos antes de guardar</li>
                <li>Estos datos son visibleslos clientes al momento del pago</li>
            </ul>
        </div>
    </div>
</div>
