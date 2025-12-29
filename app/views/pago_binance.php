<div class="max-w-xl mx-auto text-center animate-fade-in">
    <div class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
        <div class="mb-8">
            <div class="w-20 h-20 bg-yellow-400 text-black rounded-3xl flex items-center justify-center mx-auto text-4xl mb-4 rotate-3 shadow-lg shadow-yellow-200">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16.624 13.9202l2.7175 2.7154-7.353 7.353-7.353-7.352 2.7175-2.7164 4.6355 4.6595 4.6356-4.6595zm4.6366-4.6366L24 12l-2.7154 2.7154-2.7154-2.7154 2.7154-2.7154zM2.7154 9.2836L5.4308 12l-2.7154 2.7164L0 12l2.7154-2.7164zM16.624 10.0798l-4.6356-4.6356-4.6355 4.6355-2.7175-2.7154 7.353-7.353 7.353 7.353-2.7175 2.7154z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Binance Pay</h1>
            <p class="text-gray-500 mt-2">Escanea el código QR desde tu app</p>
        </div>

        <div class="bg-white p-4 rounded-3xl border-2 border-dashed border-gray-200 mb-8 inline-block shadow-sm">
            <!-- Simulated QR Code -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($url) ?>&bgcolor=ffffff" alt="Scan QR" class="w-48 h-48 rounded-lg mx-auto">
        </div>

        <div class="bg-gray-50 p-6 rounded-2xl mb-8 border border-gray-100 text-left">
            <div class="flex justify-between mb-2">
                <span class="text-gray-500">Monto a pagar:</span>
                <span class="font-bold text-xl text-yellow-600">$<?= number_format($pedido['monto'], 2) ?> USDT</span>
            </div>
            <div class="flex justify-between items-center text-xs text-gray-400 mt-2">
                 <span>ID de Orden: #<?= $pedido['id'] ?></span>
                 <span class="flex items-center text-green-500 font-medium">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span> Esperando pago
                 </span>
            </div>
        </div>

        <a href="<?= $url ?>" target="_blank" class="block w-full bg-[#FCD535] hover:bg-[#F0C930] text-gray-900 font-bold py-4 px-6 rounded-2xl shadow-lg transition duration-300 mb-4 flex items-center justify-center">
            Pagar en App de Binance <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>

        <a href="/pago/estado/<?= $pedido['id'] ?>" class="text-sm text-gray-500 hover:text-violet-600 font-medium transition-colors">
            ¿Ya pagaste? Verificar estado
        </a>
    </div>
</div>
