<?php
// Terms of Service View
?>
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-foreground mb-4">Términos de Servicio</h1>
        <div class="h-1 w-20 bg-gradient-to-r from-primary to-accent mx-auto rounded-full"></div>
    </div>

    <div class="bg-card/50 backdrop-blur border border-border rounded-3xl p-8 md:p-12 shadow-xl space-y-8">
        <section>
            <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
                <span class="p-2 rounded-lg bg-primary/10 text-primary">📜</span>
                1. Aceptación de los Términos
            </h2>
            <p class="text-muted-foreground leading-relaxed">
                Al acceder y utilizar FearSold, aceptas cumplir y estar sujeto a los siguientes términos y condiciones. Si no estás de acuerdo con alguna parte de estos términos, no podrás acceder al servicio.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
                <span class="p-2 rounded-lg bg-primary/10 text-primary">💎</span>
                2. Servicios de Recarga
            </h2>
            <p class="text-muted-foreground leading-relaxed">
                FearSold proporciona servicios de recarga de diamantes y monedas para juegos móviles. Nos esforzamos por procesar las transacciones de manera instantánea, pero los tiempos pueden variar según la disponibilidad del servidor del juego.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
                <span class="p-2 rounded-lg bg-primary/10 text-primary">💳</span>
                3. Pagos y Reembolsos
            </h2>
            <p class="text-muted-foreground leading-relaxed">
                Todos los pagos son procesados de forma segura. Debido a la naturaleza de los bienes digitales, las recargas enviadas correctamente no son reembolsables. Por favor, verifica tu ID de jugador antes de confirmar la compra.
            </p>
        </section>
        
        <div class="pt-8 border-t border-border/40 text-center text-sm text-muted-foreground">
            Última actualización: <?= date('d/m/Y') ?>
        </div>
    </div>
</div>
