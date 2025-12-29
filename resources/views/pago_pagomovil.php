<div class="max-w-xl mx-auto animate-fade-in">
    <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Pago Móvil</h1>
            <p class="text-gray-500">Realiza el pago y reporta tu referencia</p>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-2xl mb-8 border border-yellow-100 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 text-yellow-100 opacity-50">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
            </div>
            <h2 class="font-bold text-gray-800 mb-4 relative z-10 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Datos del Beneficiario
            </h2>
            <div class="space-y-3 text-gray-700 relative z-10 text-sm">
                <div class="flex justify-between border-b border-yellow-200 pb-2"><span>Banco:</span> <span class="font-bold select-all">Banesco (0134)</span></div>
                <div class="flex justify-between border-b border-yellow-200 pb-2"><span>Teléfono:</span> <span class="font-bold select-all">0412-1234567</span></div>
                <div class="flex justify-between border-b border-yellow-200 pb-2"><span>Cédula:</span> <span class="font-bold select-all">V-12.345.678</span></div>
                <div class="flex justify-between pt-1"><span>Monto a Transferir:</span> <span class="font-black text-violet-600 text-lg">$<?= number_format($pedido['monto'], 2) ?></span></div>
            </div>
        </div>

        <form action="/pago/pagomovil/confirmar" method="POST" enctype="multipart/form-data" class="space-y-6" id="paymentForm">
            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Número de Referencia</label>
                <div class="relative">
                    <input type="text" name="referencia" required placeholder="Ej: 12345678" pattern="[0-9]+" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-500 transition-all font-medium">
                    <div class="absolute left-4 top-[18px] text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Comprobante de Pago</label>
                <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-violet-400 hover:bg-violet-50 transition-all cursor-pointer group" id="dropZone">
                    <input type="file" name="comprobante" id="fileInput" accept="image/*,application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto group-hover:bg-white group-hover:text-violet-500 transition-colors shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <p class="text-sm text-gray-500 font-medium group-hover:text-violet-700">Haz clic o arrastra tu comprobante aquí</p>
                        <p class="text-xs text-gray-400" id="fileName">Soporta: JPG, PNG, PDF</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-violet-200 transform hover:-translate-y-1 transition duration-300">
                Confirmar Pago
            </button>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const dropZone = document.getElementById('dropZone');

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            fileName.textContent = 'Archivo seleccionado: ' + e.target.files[0].name;
            fileName.classList.add('text-violet-600', 'font-bold');
            dropZone.classList.add('border-violet-500', 'bg-violet-50');
        }
    });

    // Simple Drag and Drop feedback
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            dropZone.classList.add('border-violet-500', 'bg-violet-50');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            dropZone.classList.remove('border-violet-500', 'bg-violet-50');
        });
    });
</script>
