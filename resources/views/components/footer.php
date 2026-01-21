<?php
// Footer Component - v0 style with dark mode support
?>

<footer class="border-t border-border/40 bg-card/50 backdrop-blur mt-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="bg-gradient-to-r from-primary to-accent p-2 rounded-lg">
                        <svg class="h-6 w-6 text-primary-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                        Fear Sold
                    </span>
                </div>
                <p class="text-sm text-muted-foreground leading-relaxed">
                    Tu tienda confiable de recargas digitales para juegos móviles. Rápido, seguro y al instante.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-semibold mb-4 text-foreground">Enlaces Rápidos</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="./" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="./#games" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            Juegos Disponibles
                        </a>
                    </li>
                    <li>
                        <a href="./soporte" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            Soporte
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="font-semibold mb-4 text-foreground">Legal</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="./terminos" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            Términos de Servicio
                        </a>
                    </li>
                    <li>
                        <a href="./privacidad" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            Política de Privacidad
                        </a>
                    </li>
                    <li>
                        <a href="./reembolsos" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            Política de Reembolso
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-semibold mb-4 text-foreground">Contacto</h3>
                <ul class="space-y-2">
                    <li class="text-sm text-muted-foreground">soporte@fearsold.com</li>
                    <li class="text-sm text-muted-foreground">WhatsApp: +58 412 XXX XXXX</li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-border/40">
            <p class="text-center text-sm text-muted-foreground">
                © <?= date('Y') ?> FearSold. Todos los derechos reservados.
            </p>
            <p class="text-center text-xs text-muted-foreground mt-2">
                FearSold no está afiliado con Moonton ni Mobile Legends. Todos los nombres y marcas comerciales pertenecen a sus respectivos dueños.
            </p>
        </div>
    </div>
</footer>
