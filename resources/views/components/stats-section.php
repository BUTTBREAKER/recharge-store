<?php
// Stats Section - Social proof with metrics
?>

<section class="py-20 relative overflow-hidden">
    <!-- Animated Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-accent/5 to-secondary/10"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-primary/30 to-accent/30 rounded-full blur-3xl animate-pulse"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat 1: Recharges -->
            <div class="group bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 text-center hover:shadow-2xl hover:shadow-primary/20 hover:border-primary/50 hover:-translate-y-2 transition-all duration-500">
                <div class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent mb-3" 
                     x-data="{ count: 0 }" 
                     x-intersect="$el.classList.contains('counting') || (() => { $el.classList.add('counting'); let interval = setInterval(() => { if (count < 2500) count += 50; else clearInterval(interval); }, 30); })()">
                    <span x-text="count + '+'">0+</span>
                </div>
                <p class="text-muted-foreground font-semibold">Recargas Completadas</p>
            </div>

            <!-- Stat 2: Customers -->
            <div class="group bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 text-center hover:shadow-2xl hover:shadow-green-500/20 hover:border-green-500/50 hover:-translate-y-2 transition-all duration-500">
                <div class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-green-500 to-emerald-600 bg-clip-text text-transparent mb-3"
                     x-data="{ count: 0 }" 
                     x-intersect="$el.classList.contains('counting') || (() => { $el.classList.add('counting'); let interval = setInterval(() => { if (count < 800) count += 20; else clearInterval(interval); }, 40); })()">
                    <span x-text="count + '+'">0+</span>
                </div>
                <p class="text-muted-foreground font-semibold">Clientes Satisfechos</p>
            </div>

            <!-- Stat 3: Delivery Time -->
            <div class="group bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 text-center hover:shadow-2xl hover:shadow-blue-500/20 hover:border-blue-500/50 hover:-translate-y-2 transition-all duration-500">
                <div class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-blue-500 to-cyan-600 bg-clip-text text-transparent mb-3">
                    &lt;5
                </div>
                <p class="text-muted-foreground font-semibold">Minutos de Entrega</p>
            </div>

            <!-- Stat 4: Success Rate -->
            <div class="group bg-card/80 backdrop-blur-sm border border-border rounded-3xl p-8 text-center hover:shadow-2xl hover:shadow-amber-500/20 hover:border-amber-500/50 hover:-translate-y-2 transition-all duration-500">
                <div class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent mb-3"
                     x-data="{ count: 0 }" 
                     x-intersect="$el.classList.contains('counting') || (() => { $el.classList.add('counting'); let interval = setInterval(() => { if (count < 99) count += 1; else clearInterval(interval); }, 30); })()">
                    <span x-text="count + '%'">0%</span>
                </div>
                <p class="text-muted-foreground font-semibold">Tasa de Éxito</p>
            </div>
        </div>
    </div>
</section>
