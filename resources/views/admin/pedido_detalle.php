<div class="mb-8 animate-fade-in">
    <a href="./admin/dashboard" class="text-muted-foreground hover:text-primary font-medium flex items-center transition-colors w-fit">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al dashboard
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-card p-8 rounded-3xl shadow-xl border border-border">
            <div class="flex justify-between items-start mb-6 border-b border-border pb-6">
                <div>
                     <span class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Pedido</span>
                    <h2 class="text-3xl font-extrabold text-foreground">#<?= str_pad($pedido['id'], 5, '0', STR_PAD_LEFT) ?></h2>
                </div>
                 <span class="px-4 py-2 rounded-xl text-sm font-bold 
                    <?= $pedido['estado'] == 'pendiente' ? 'bg-yellow-500/10 text-yellow-500' : '' ?>
                    <?= $pedido['estado'] == 'confirmado' ? 'bg-blue-500/10 text-blue-500' : '' ?>
                    <?= $pedido['estado'] == 'realizada' ? 'bg-green-500/10 text-green-500' : '' ?>
                    <?= $pedido['estado'] == 'cancelado' ? 'bg-destructive/10 text-destructive' : '' ?>
                ">
                    <?= ucfirst($pedido['estado']) ?>
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-muted p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-muted-foreground uppercase mb-1">Juego & Paquete</span>
                    <div class="font-bold text-foreground text-lg"><?= $pedido['juego'] ?></div>
                    <div class="text-primary font-medium"><?= $pedido['paquete'] ?></div>
                </div>
                 <div class="bg-muted p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-muted-foreground uppercase mb-1">Datos Jugador</span>
                    <div class="font-mono text-foreground text-lg"><?= $pedido['player_id'] ?></div>
                    <div class="text-muted-foreground text-sm">Server: <?= $pedido['server_id'] ?></div>
                </div>
                 <div class="bg-muted p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-muted-foreground uppercase mb-1">Monto Total</span>
                    <div class="font-bold text-foreground text-xl">$<?= number_format($pedido['monto'], 2) ?> USD</div>
                </div>
                 <div class="bg-muted p-4 rounded-2xl">
                    <span class="block text-xs font-bold text-muted-foreground uppercase mb-1">Contacto</span>
                    <div class="font-bold text-foreground"><?= $pedido['telefono'] ?></div>
                     <div class="text-muted-foreground text-xs"><?= $pedido['fecha'] ?></div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-border">
                <h3 class="font-bold text-foreground mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Gestionar Estado
                </h3>
                <form action="./admin/pedido/actualizar" method="POST" class="bg-muted p-2 rounded-2xl flex gap-2">
                    <?php csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                    <select name="estado" class="flex-grow p-3 bg-input border-0 rounded-xl outline-none shadow-sm focus:ring-2 focus:ring-primary/20 font-medium text-foreground">
                        <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>🟡 Pendiente</option>
                        <option value="confirmado" <?= $pedido['estado'] == 'confirmado' ? 'selected' : '' ?>>🔵 Confirmado (Pago OK)</option>
                        <option value="realizada" <?= $pedido['estado'] == 'realizada' ? 'selected' : '' ?>>🟢 Realizada (Entregado)</option>
                        <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>🔴 Cancelado</option>
                    </select>
                    <button type="submit" class="bg-primary text-primary-foreground px-8 py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                        Actualizar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Proof -->
    <div class="lg:col-span-1">
        <div class="bg-card p-8 rounded-3xl shadow-xl border border-border h-fit sticky top-24">
            <h2 class="text-xl font-bold mb-6 text-foreground">Comprobante de Pago</h2>
            <?php if ($pago) : ?>
                <div class="space-y-6">
                    <div class="flex items-center p-3 bg-muted rounded-xl">
                         <div class="w-10 h-10 <?= $pago['provider'] == 'binance' ? 'bg-yellow-500/10 text-yellow-500' : 'bg-green-500/10 text-green-500' ?> rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-xs text-muted-foreground uppercase font-bold">Método</div>
                             <div class="font-bold capitalize text-foreground"><?= $pago['provider'] ?></div>
                        </div>
                    </div>

                    <?php if ($pago['referencia']) : ?>
                    <div class="block">
                         <span class="text-xs text-muted-foreground uppercase font-bold">Referencia</span>
                         <div class="font-mono bg-muted p-2 rounded-lg mt-1 text-center select-all text-foreground"><?= $pago['referencia'] ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if ($pago['comprobante']) : ?>
                         <div class="group relative cursor-pointer" onclick="window.open('./<?= $pago['comprobante'] ?>', '_blank')">
                            <span class="text-xs text-muted-foreground uppercase font-bold mb-2 block">Imagen Adjunta</span>
                        <div class="relative overflow-hidden rounded-2xl border-2 border-border group-hover:border-primary transition-all">
                                <img src="./<?= $pago['comprobante'] ?>" class="w-full h-48 object-cover transform group-hover:scale-105 transition duration-500" alt="Comprobante">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center">
                                    <span class="opacity-0 group-hover:opacity-100 bg-card/90 px-3 py-1 rounded-full text-xs font-bold shadow-sm backdrop-blur-sm text-foreground">Ver Completo</span>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="p-6 border-2 border-dashed border-border rounded-2xl text-center">
                            <span class="text-muted-foreground text-sm">Sin comprobante visual</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-muted rounded-full flex items-center justify-center mx-auto mb-3 text-2xl grayscale">🏺</div>
                    <p class="text-muted-foreground text-sm italic">El cliente aún no ha reportado el pago.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
