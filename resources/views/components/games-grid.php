<?php

// ...

?>

<div id="games" class="py-12">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900">Juegos Disponibles</h2>
        <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">Selecciona tu juego para ver las ofertas exclusivas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card Mobile Legends -->
        <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
            <div class="h-48 bg-gradient-to-br from-blue-600 to-violet-600 flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10 group-hover:opacity-0 transition-opacity"></div>
                <span class="text-7xl drop-shadow-lg filter group-hover:scale-110 transition-transform duration-300">📱</span>
            </div>
            <div class="p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Mobile Legends</h3>
                <p class="text-sm text-gray-500 mb-6 uppercase tracking-wide font-semibold">Moonton</p>
                <p class="text-gray-600 mb-6 line-clamp-2">Recarga diamantes directos a tu ID. Proceso 100% seguro y automático.</p>

                <a href="/juego/mobile-legends" class="block w-full text-center bg-violet-50 text-violet-700 font-bold py-3 px-6 rounded-xl hover:bg-violet-600 hover:text-white transition-all duration-300">
                    Ver Paquetes &rarr;
                </a>
            </div>
        </div>

        <!-- Coming Soon Card -->
        <div class="relative bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 p-8 flex flex-col items-center justify-center text-center opacity-75">
            <span class="text-5xl mb-4 grayscale">🎮</span>
            <h3 class="text-xl font-bold text-gray-400">Próximamente</h3>
            <p class="text-gray-400 mt-2">Free Fire, PUBG y más...</p>
        </div>
    </div>
</div>
