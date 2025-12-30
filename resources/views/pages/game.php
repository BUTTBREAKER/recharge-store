<?php

foreach ($packages ?? [] as $index => $package) {
    $packages[$index]['id'] = $index + 1;

    if (rand(0, 1)) {
        $packages[$index]['bonus'] = '+ ' . rand(8, 210) . ' Bonus';
        $packages[$index]['tag'] = 'Popular';
    }
}

// $packages = [
//     ['id' => 1, 'nombre' => '86 Diamantes', 'precio' => 2.00, 'bonus' => '+ 8 Bonus'],
//     ['id' => 2, 'nombre' => '172 Diamantes', 'precio' => 4.00, 'bonus' => '+ 16 Bonus'],
//     ['id' => 3, 'nombre' => '257 Diamantes', 'precio' => 6.00, 'bonus' => '+ 25 Bonus'],
//     ['id' => 4, 'nombre' => '706 Diamantes', 'precio' => 15.00, 'bonus' => '+ 70 Bonus'],
//     ['id' => 5, 'nombre' => '2195 Diamantes', 'precio' => 45.00, 'bonus' => '+ 210 Bonus'],
//     ['id' => 6, 'nombre' => 'Twilight Pass', 'precio' => 10.00, 'tag' => 'Popular'],
// ];

?>

<div class="mb-8">
    <a href="./" class="group flex items-center text-gray-500 hover:text-violet-600 transition-colors">
        <div class="mr-2 p-2 bg-white rounded-full shadow-sm group-hover:bg-violet-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </div>
        <span class="font-medium">Volver al inicio</span>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
    <!-- Packages Selection -->
    <div class="lg:col-span-2 space-y-8 animate-fade-in">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-violet-600 rounded-xl flex items-center justify-center text-2xl shadow-lg">📱</div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                    <?= $packages[0]['juego'] ?? 'Mobile Legends' ?>
                </h1>
                <p class="text-gray-500">Selecciona tu paquete de diamantes</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($packages ?? [] as $p) : ?>
                <label class="relative group">
                    <input
                        type="radio"
                        name="paquete_radio"
                        value="<?= $p['nombre'] ?>"
                        data-precio="<?= $p['precio'] ?>"
                        class="peer sr-only"
                        onchange="updateSelection(this)">
                    <div class="h-full bg-white border-2 border-gray-100 p-5 rounded-2xl cursor-pointer hover:border-violet-300 hover:shadow-lg peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:shadow-violet-200 peer-checked:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <?php if (isset($p['tag'])) : ?>
                            <span class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-xl rounded-tr-lg shadow-sm">
                                Popular
                            </span>
                        <?php endif ?>

                        <div class="mb-4 text-center">
                            <div class="text-4xl mb-2 drop-shadow-sm">💎</div>
                            <div class="font-bold text-gray-800 text-lg"><?= $p['nombre'] ?></div>
                            <?php if (isset($p['bonus'])) : ?>
                                <div class="text-xs font-medium text-emerald-500 bg-emerald-50 inline-block px-2 py-1 rounded-full mt-1">
                                    <?= $p['bonus'] ?>
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="text-center pt-4 border-t border-gray-50">
                            <div class="text-lg font-bold text-gray-900">$<?= number_format($p['precio'], 2) ?></div>
                            <div class="text-xs text-gray-500 font-medium mt-1">
                                Bs <?= number_format($p['precio'] * $exchangeRate, 2) ?>
                            </div>
                        </div>
                    </div>
                </label>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Purchase Form -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 lg:p-8 rounded-3xl shadow-xl border border-gray-100 sticky top-24 transition-all hover:shadow-2xl">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="bg-violet-100 text-violet-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">2</span>
                Datos de Jugador
            </h2>

            <form action="/checkout" method="POST" id="rechargeForm" class="space-y-5">
                <input type="hidden" name="paquete" id="selected_paquete">
                <input type="hidden" name="monto" id="selected_monto">
                <input type="hidden" name="juego" value="Mobile Legends">

                <div class="space-y-4">
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Player ID</label>
                        <input type="text" name="player_id" required placeholder="12345678" pattern="[0-9]+" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-violet-100 focus:border-violet-500 outline-none transition-all font-medium text-gray-800 placeholder-gray-400">
                        <div class="absolute left-4 top-[35px] text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Server ID</label>
                        <input type="text" name="server_id" required placeholder="1234" pattern="[0-9]+" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-violet-100 focus:border-violet-500 outline-none transition-all font-medium text-gray-800 placeholder-gray-400">
                        <div class="absolute left-4 top-[35px] text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Teléfono / WhatsApp</label>
                        <input type="tel" name="telefono" required placeholder="0412 123 4567" class="w-full p-4 pl-12 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-violet-100 focus:border-violet-500 outline-none transition-all font-medium text-gray-800 placeholder-gray-400">
                        <div class="absolute left-4 top-[35px] text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <div class="flex justify-between items-end mb-6">
                            <div class="text-sm text-gray-500">Total a pagar</div>
                            <div>
                                <span class="font-bold text-3xl text-gray-900" id="display_total">$0.00</span>
                                <span class="text-xs text-gray-400 uppercase font-medium ml-1">USD</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-gray-900 font-bold py-4 px-6 rounded-2xl shadow-lg shadow-yellow-200 transform hover:-translate-y-1 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none" id="btnSubmit" disabled>
                            Continuar al Pago &rarr;
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-4 flex items-center justify-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            Pago 100% Seguro
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateSelection(radio) {
        document.getElementById('selected_paquete').value = radio.value;
        document.getElementById('selected_monto').value = radio.dataset.precio;
        document.getElementById('display_total').innerText = '$' + parseFloat(radio.dataset.precio).toFixed(2);

        // Smooth scroll to form on mobile only
        if (window.innerWidth < 1024) {
            document.getElementById('rechargeForm').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
        document.getElementById('btnSubmit').disabled = false;
    }
</script>
