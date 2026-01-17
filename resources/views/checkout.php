<div class="max-w-3xl mx-auto animate-fade-in">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-foreground">Finalizar Compra</h1>
        <p class="text-muted-foreground mt-2">Estás a un paso de recibir tus diamantes.</p>
    </div>
    
    <div class="bg-card rounded-3xl shadow-xl border border-border overflow-hidden mb-8">
        <div class="bg-muted/50 px-8 py-6 border-b border-border flex justify-between items-center">
            <h2 class="font-bold text-foreground">Resumen del Pedido</h2>
            <span class="text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 px-3 py-1 rounded-full">Pendiente</span>
        </div>
        <div class="p-8">
            <div class="flex items-center space-x-4 mb-6 pb-6 border-b border-border">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-3xl">📱</div>
                <div>
                    <h3 class="font-bold text-lg text-foreground"><?= $pedido['juego'] ?></h3>
                    <p class="text-muted-foreground text-sm">ID: <?= $pedido['player_id'] ?> (<?= $pedido['server_id'] ?>)</p>
                </div>
            </div>
            
            <div class="flex justify-between items-center bg-muted/50 p-4 rounded-xl">
                <div>
                    <p class="text-sm text-muted-foreground">Paquete seleccionado</p>
                    <p class="font-bold text-foreground"><?= $pedido['paquete'] ?></p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-muted-foreground">Total a pagar</p>
                    <p class="font-bold text-2xl text-primary">$<?= number_format($pedido['monto'], 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <form action="/procesar-pago" method="POST" class="space-y-8">
        <?php csrf_field() ?>
        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
        
        <div class="space-y-4">
            <h2 class="text-xl font-bold text-foreground">Selecciona Método de Pago</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="relative group cursor-pointer">
                    <input type="radio" name="metodo" value="pagomovil" class="peer sr-only" required>
                    <div class="p-6 bg-card border-2 border-border rounded-3xl hover:border-primary/50 hover:shadow-lg peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:shadow-primary/20 peer-checked:shadow-xl transition-all duration-300 h-full">
                        <div class="flex items-center justify-between mb-4">
                             <div class="w-10 h-10 bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary transition-colors"></div>
                        </div>
                        <h3 class="font-bold text-foreground text-lg mb-1">Pago Móvil</h3>
                        <p class="text-sm text-muted-foreground">
                            Transferencia manual en Bolívares. Reporte con captura.
                        </p>
                    </div>
                </label>

                <label class="relative group cursor-pointer">
                    <input type="radio" name="metodo" value="binance" class="peer sr-only" required>
                    <div class="p-6 bg-card border-2 border-border rounded-3xl hover:border-yellow-300/50 hover:shadow-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-500/5 peer-checked:shadow-yellow-500/20 peer-checked:shadow-xl transition-all duration-300 h-full">
                         <div class="flex items-center justify-between mb-4">
                             <div class="w-10 h-10 bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-border peer-checked:border-yellow-500 peer-checked:bg-yellow-500 transition-colors"></div>
                        </div>
                        <h3 class="font-bold text-foreground text-lg mb-1">Binance Pay</h3>
                        <p class="text-sm text-muted-foreground">
                            Pago automático y rápido con USDT. <span class="text-xs bg-black text-white dark:bg-white dark:text-black px-2 py-0.5 rounded ml-1">Recomendado</span>
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent hover:opacity-90 text-primary-foreground font-bold py-5 px-6 rounded-2xl shadow-lg transform hover:-translate-y-1 transition duration-300 flex items-center justify-center space-x-2 text-lg">
            <span>Confirmar Pedido</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
        
        <p class="text-center text-xs text-muted-foreground">
            Al continuar confirmas que los datos ingresados son correctos.
        </p>
    </form>
</div>
