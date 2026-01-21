<?php
// Privacy Policy View
?>
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-foreground mb-4">Política de Privacidad</h1>
        <div class="h-1 w-20 bg-gradient-to-r from-primary to-accent mx-auto rounded-full"></div>
    </div>

    <div class="bg-card/50 backdrop-blur border border-border rounded-3xl p-8 md:p-12 shadow-xl space-y-8">
        <section>
            <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
                <span class="p-2 rounded-lg bg-primary/10 text-primary">🔒</span>
                Recopilación de Datos
            </h2>
            <p class="text-muted-foreground leading-relaxed">
                Solo recopilamos la información necesaria para procesar tus pedidos, como tu ID de jugador, correo electrónico (para recibos) y detalles de pago. No compartimos tu información con terceros.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
                <span class="p-2 rounded-lg bg-primary/10 text-primary">🛡️</span>
                Seguridad
            </h2>
            <p class="text-muted-foreground leading-relaxed">
                Tomamos muy en serio la seguridad de tus datos. Utilizamos encriptación SSL y procesadores de pago seguros para proteger tu información personal y financiera.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
                <span class="p-2 rounded-lg bg-primary/10 text-primary">🍪</span>
                Cookies
            </h2>
            <p class="text-muted-foreground leading-relaxed">
                Utilizamos cookies esenciales para mejorar tu experiencia de navegación y recordar tus preferencias de tema (modo oscuro/claro).
            </p>
        </section>
        
        <div class="pt-8 border-t border-border/40 text-center text-sm text-muted-foreground">
            Última actualización: <?= date('d/m/Y') ?>
        </div>
    </div>
</div>
