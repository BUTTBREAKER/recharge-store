<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900">Configuración del Sistema</h1>
        <p class="text-gray-500 mt-1">Gestiona la tasa de cambio y otras configuraciones</p>
    </div>
    <a href="/admin/dashboard" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al Dashboard
    </a>
</div>

<?php if (isset($_GET['success'])) : ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-700 font-medium">Tasa de cambio actualizada correctamente</p>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])) : ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
        <p class="text-red-700 font-medium">Error: la tasa debe ser un número mayor a 0</p>
    </div>
</div>
<?php endif; ?>

<!-- Tasa de Cambio -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
    <div class="flex items-center mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center text-2xl mr-4">
            💱
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tasa de Cambio</h2>
            <p class="text-sm text-gray-500">Configura el precio del dólar en bolívares para Pago Móvil</p>
        </div>
    </div>

    <form action="/admin/config/exchange-rate" method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-3">
                Tasa USD → Bs (Bolívares)
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-bold text-lg">Bs</span>
                </div>
                <input 
                    type="number" 
                    name="exchange_rate" 
                    step="0.01" 
                    min="0.01"
                    value="<?= $exchangeRate ?>"
                    class="w-full pl-14 pr-4 py-4 text-2xl font-bold bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 outline-none transition text-gray-900"
                    placeholder="36.50"
                    required
                >
            </div>
            <p class="mt-2 text-sm text-gray-500">
                Los precios en dólares se multiplicarán por esta tasa para mostrar el equivalente en bolívares
            </p>
        </div>

        <!-- Vista Previa -->
        <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-xl p-6 border-2 border-violet-200">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Vista Previa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 text-center border border-violet-200">
                    <p class="text-sm text-gray-500 mb-1">Paquete $2.00</p>
                    <p class="text-xl font-bold text-gray-900" id="preview_2">Bs <?= number_format(2 * $exchangeRate, 2) ?></p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center border border-violet-200">
                    <p class="text-sm text-gray-500 mb-1">Paquete $10.00</p>
                    <p class="text-xl font-bold text-gray-900" id="preview_10">Bs <?= number_format(10 * $exchangeRate, 2) ?></p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center border border-violet-200">
                    <p class="text-sm text-gray-500 mb-1">Paquete $45.00</p>
                    <p class="text-xl font-bold text-gray-900" id="preview_45">Bs <?= number_format(45 * $exchangeRate, 2) ?></p>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button 
                type="submit" 
                class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg shadow-green-200 transition transform hover:-translate-y-1">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<!-- Información -->
<div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        <div class="text-sm text-blue-700">
            <p class="font-bold mb-2">Sobre la tasa de cambio:</p>
            <ul class="space-y-1 list-disc list-inside">
                <li>Esta tasa se usa para calcular precios en bolívares en tiempo real</li>
                <li>Los usuarios verán ambos precios (USD y Bs) al seleccionar paquetes</li>
                <li>Actualiza esta tasa regularmente según el mercado</li>
                <li>Los cambios se aplican inmediatamente en toda la tienda</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Preview live update
const rateInput = document.querySelector('input[name="exchange_rate"]');
rateInput.addEventListener('input', function() {
    const rate = parseFloat(this.value) || 0;
    document.getElementById('preview_2').textContent = 'Bs ' + (2 * rate).toFixed(2);
    document.getElementById('preview_10').textContent = 'Bs ' + (10 * rate).toFixed(2);
    document.getElementById('preview_45').textContent = 'Bs ' + (45 * rate).toFixed(2);
});
</script>
