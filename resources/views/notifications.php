<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Mis Notificaciones</h1>
            <p class="text-gray-500 mt-1">Alertas y estado de tus recargas en tiempo real</p>
        </div>
        <div class="flex gap-3">
            <?php if (!empty($notificaciones)) : ?>
            <a href="/notifications?read=all" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-5 py-2.5 rounded-xl font-bold transition text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Marcar todas como leídas
            </a>
            <?php endif; ?>
            <a href="/" class="bg-violet-600 text-white hover:bg-violet-700 px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-violet-200 text-sm flex items-center">
                Volver a la Tienda
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lista de Notificaciones -->
        <div class="lg:col-span-2 space-y-4">
            <?php if (empty($notificaciones)) : ?>
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-16 text-center">
                <div class="text-6xl mb-6 opacity-20">📭</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Sin notificaciones nuevas</h3>
                <p class="text-gray-500">Te avisaremos por aquí cuando cambie el estado de tus pedidos.</p>
            </div>
            <?php else : ?>
                <?php foreach ($notificaciones as $n) : ?>
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 transition hover:shadow-lg relative overflow-hidden group <?= !$n['leido'] ? 'border-l-4 border-l-violet-500' : '' ?>">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl
                            <?= stripos($n['titulo'], 'Pago') !== false ? 'bg-blue-50 text-blue-600' : '' ?>
                            <?= stripos($n['titulo'], 'Recarga') !== false ? 'bg-green-50 text-green-600' : '' ?>
                            <?= stripos($n['titulo'], 'Problema') !== false ? 'bg-red-50 text-red-600' : '' ?>
                            <?= !in_array($n['tipo'], ['pedido_actualizado']) ? 'bg-violet-50 text-violet-600' : '' ?>
                        ">
                            <?php
                            if (stripos($n['titulo'], 'Pago') !== false) {
                                echo '💳';
                            } elseif (stripos($n['titulo'], 'Recarga') !== false) {
                                echo '💎';
                            } elseif (stripos($n['titulo'], 'Problema') !== false) {
                                echo '⚠️';
                            } else {
                                echo '🔔';
                            }
                            ?>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-bold text-gray-900 <?= !$n['leido'] ? 'pr-8' : '' ?>"><?= htmlspecialchars($n['titulo']) ?></h3>
                                <span class="text-xs text-gray-400 font-medium"><?= date('d/m H:i', strtotime($n['created_at'])) ?></span>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-3"><?= htmlspecialchars($n['mensaje']) ?></p>
                            
                            <?php if ($n['link']) : ?>
                            <a href="<?= $n['link'] ?>" class="text-xs font-bold text-violet-600 hover:text-violet-700 flex items-center group-hover:translate-x-1 transition-transform">
                                Ver detalles <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!$n['leido']) : ?>
                        <div class="absolute top-4 right-4 w-2 h-2 bg-violet-500 rounded-full"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar: Resumen de Pedidos -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="bg-violet-100 text-violet-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3 text-sm">📦</span>
                    Últimas Recargas
                </h3>
                
                <?php if (empty($pedidos)) : ?>
                    <p class="text-sm text-gray-500 text-center py-4 italic">No hay pedidos recientes</p>
                <?php else : ?>
                    <div class="space-y-3">
                        <?php foreach ($pedidos as $p) : ?>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold text-gray-400">#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase
                                    <?= $p['estado'] == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' ?>
                                    <?= $p['estado'] == 'confirmado' ? 'bg-blue-100 text-blue-700' : '' ?>
                                    <?= $p['estado'] == 'realizada' ? 'bg-green-100 text-green-700' : '' ?>
                                    <?= $p['estado'] == 'cancelado' ? 'bg-red-100 text-red-700' : '' ?>
                                ">
                                    <?= $p['estado'] ?>
                                </span>
                            </div>
                            <p class="text-sm font-bold text-gray-800 line-clamp-1"><?= $p['paquete'] ?></p>
                            <p class="text-[10px] text-gray-500 mt-1"><?= date('d M, Y', strtotime($p['fecha'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="/profile/orders" class="block text-center mt-4 text-sm font-bold text-violet-600 hover:text-violet-700 py-2 bg-violet-50 rounded-lg">
                        Ver todo el historial
                    </a>
                <?php endif; ?>
            </div>

            <!-- Card de Ayuda -->
            <div class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-3xl shadow-xl p-6 text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h3 class="font-bold mb-2">¿Necesitas ayuda?</h3>
                    <p class="text-xs text-violet-100 mb-4 leading-relaxed">Si tienes dudas sobre el estado de tu recarga, nuestro equipo está listo para ayudarte en WhatsApp.</p>
                    <a href="https://wa.me/tu_numero" target="_blank" class="inline-block bg-white text-violet-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-violet-50 transition">
                        Contactar Soporte
                    </a>
                </div>
                <!-- Decoración -->
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>
    </div>
</div>
