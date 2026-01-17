<div class="max-w-xl mx-auto animate-fade-in">
    <div class="bg-card p-8 rounded-3xl shadow-xl border border-border">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-foreground">Pago Móvil</h1>
            <p class="text-muted-foreground">Realiza el pago y reporta tu referencia</p>
        </div>
        
        <div class="bg-muted p-6 rounded-2xl mb-8 border border-border relative overflow-hidden">
            <div class="absolute -right-6 -top-6 text-primary/10 opacity-50">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
            </div>
            <h2 class="font-bold text-foreground mb-4 relative z-10 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Datos del Beneficiario
            </h2>
            <div class="space-y-3 text-muted-foreground relative z-10 text-sm">
                <div class="flex justify-between border-b border-border pb-2"><span>Banco:</span> <span class="font-bold text-foreground select-all">Banesco (0134)</span></div>
                <div class="flex justify-between border-b border-border pb-2"><span>Teléfono:</span> <span class="font-bold text-foreground select-all">0412-1234567</span></div>
                <div class="flex justify-between border-b border-border pb-2"><span>Cédula:</span> <span class="font-bold text-foreground select-all">V-12.345.678</span></div>
                <div class="flex justify-between pt-1"><span>Monto a Transferir:</span> <span class="font-black text-primary text-lg">$<?= number_format($pedido['monto'], 2) ?></span></div>
            </div>
        </div>

        <form action="/pago/pagomovil/confirmar" method="POST" enctype="multipart/form-data" class="space-y-6" id="paymentForm">
            <?php csrf_field() ?>
            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
            
            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Número de Referencia</label>
                <div class="relative">
                    <input type="text" name="referencia" required placeholder="Ej: 12345678" pattern="[0-9]+" class="w-full p-4 pl-12 bg-input border border-input rounded-xl outline-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all font-medium text-foreground placeholder:text-muted-foreground">
                    <div class="absolute left-4 top-[18px] text-muted-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-foreground mb-2">Comprobante de Pago</label>
                <div class="relative border-2 border-dashed border-input rounded-2xl p-6 text-center hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer group" id="dropZone">
                    <input type="file" name="comprobante" id="fileInput" accept="image/*,application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 bg-muted text-muted-foreground rounded-full flex items-center justify-center mx-auto group-hover:bg-card group-hover:text-primary transition-colors shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <p class="text-sm text-foreground font-medium group-hover:text-primary">Haz clic o arrastra tu comprobante aquí</p>
                        <p class="text-xs text-muted-foreground" id="fileName">Soporta: JPG, PNG, PDF</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent hover:opacity-90 text-primary-foreground font-bold py-4 px-6 rounded-2xl shadow-lg transform hover:-translate-y-1 transition duration-300">
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
            fileName.classList.add('text-primary', 'font-bold');
            dropZone.classList.add('border-primary', 'bg-primary/5');
        }
    });

    // Simple Drag and Drop feedback
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            dropZone.classList.add('border-primary', 'bg-primary/5');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            dropZone.classList.remove('border-primary', 'bg-primary/5');
        });
    });
</script>
