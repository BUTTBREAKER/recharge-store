<?php

// ...

?>

<footer class="bg-white border-t border-gray-100 mt-12">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:justify-between">
            <div class="mb-8 md:mb-0">
                <h2 class="text-lg font-bold text-gray-900">SisifoStore</h2>
                <p class="mt-2 text-sm text-gray-500">Recargas seguras y rápidas para Mobile Legends.</p>
            </div>
            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Legal</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="./legal" class="text-base text-gray-500 hover:text-gray-900">
                                Aviso Legal
                            </a>
                        </li>
                        <li>
                            <a href="./reembolsos" class="text-base text-gray-500 hover:text-gray-900">
                                Reembolsos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-200 pt-8 text-center md:text-left">
            <p class="text-sm text-gray-400">
                &copy; <?= date('Y') ?> SisifoStore. Todos los derechos reservados.
            </p>
            <p class="text-xs text-gray-300 mt-2">
                Este sitio no está afiliado a Moonton. Mobile Legends: Bang Bang es marca registrada de Moonton.
            </p>
        </div>
    </div>
</footer>
