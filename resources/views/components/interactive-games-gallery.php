<?php

use flight\Container;
use Leaf\Db;
use Symfony\Component\String\Slugger\SluggerInterface;

$db = Container::getInstance()->get(Db::class);

$games = $db->query('select distinct juego as game from productos')->fetchAll() ?: [];
$slug = Container::getInstance()->get(SluggerInterface::class)->slug(...);

// Game images from stock search for marquee
$gameImages = [
    'https://pixabay.com/get/g0ee2463f211b44ea977b23eedfff912ec95b33739eac4c6603f7260439ad410f53131f5f0787ec61d5b55d770366367a.jpg',
    'https://images.unsplash.com/photo-1626240130051-68871c71de47',
    'https://images.unsplash.com/photo-1564049489314-60d154ff107d',
    'https://images.pexels.com/photos/34179707/pexels-photo-34179707.jpeg',
    'https://images.pexels.com/photos/12660509/pexels-photo-12660509.jpeg',
];

?>

<div id="games" class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-4xl md:text-5xl font-extrabold text-foreground mb-4">
                Juegos Disponibles
            </h2>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto">
                Selecciona tu juego favorito y recarga al instante
            </p>
        </div>

        <!-- Interactive Gallery with Hover Effects -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <?php foreach ($games as $index => $game) : ?>
                <?php
                $gameSlug = $slug($game['game']);
                $iconPath = "assets/images/games/{$gameSlug}/icon.png";
                $hasIcon = file_exists(__DIR__ . '/../../../public/' . $iconPath);
                ?>
                <a href="./juego/<?= $slug($game['game']) ?>" class="group block relative overflow-hidden rounded-3xl aspect-[4/3] shadow-xl hover:shadow-2xl transition-all duration-500">
                    <!-- Base Image -->
                    <div class="absolute inset-0">
                        <?php if ($hasIcon) : ?>
                            <img 
                                src="/<?= $iconPath ?>" 
                                alt="<?= $game['game'] ?>" 
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                            >
                        <?php else : ?>
                            <div class="w-full h-full bg-gradient-to-br from-primary via-accent to-secondary flex items-center justify-center">
                                <span class="text-9xl drop-shadow-2xl filter transform group-hover:scale-110 transition-transform duration-700">🎮</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Gradient Overlay (appears on hover) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-background via-background/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Content (slides up on hover) -->
                    <div class="absolute inset-0 flex flex-col justify-end p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <!-- Popular Badge -->
                        <?php if ($index === 0): ?>
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-secondary to-amber-400 text-secondary-foreground px-4 py-2 rounded-full text-sm font-bold shadow-lg transform translate-x-20 group-hover:translate-x-0 transition-transform duration-500">
                            ⭐ Popular
                        </div>
                        <?php endif; ?>

                        <!-- Title (always visible but transforms) -->
                        <h3 class="text-3xl font-extrabold text-white mb-3 transform group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                            <?= $game['game'] ?>
                        </h3>

                        <!-- Description (fades in on hover) -->
                        <p class="text-white/90 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 leading-relaxed">
                            Recarga diamantes directos a tu ID. Proceso 100% seguro y automático.
                        </p>

                        <!-- CTA Button (slides up on hover) -->
                        <div class="flex items-center justify-between opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-200">
                            <span class="text-white font-bold text-lg">Ver Paquetes</span>
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Glow Effect on Hover -->
                    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-primary/50 to-accent/50 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </a>
            <?php endforeach ?>

            <!-- Coming Soon Card -->
            <div class="relative bg-muted/50 backdrop-blur-sm rounded-3xl border-2 border-dashed border-border p-12 flex flex-col items-center justify-center text-center aspect-[4/3] hover:border-primary/50 transition-all duration-300 group">
                <div class="text-7xl mb-4 grayscale group-hover:grayscale-0 transition-all duration-300">🎮</div>
                <h3 class="text-2xl font-bold text-muted-foreground group-hover:text-foreground transition-colors">Próximamente</h3>
                <p class="text-muted-foreground mt-2">Free Fire, PUBG Mobile y más...</p>
            </div>
        </div>

        <!-- Marquee Gallery -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-card/50 to-muted/50 backdrop-blur-sm border border-border/40 py-8">
            <div class="text-center mb-6">
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">Jugadores activos en</p>
            </div>

            <!-- Marquee Container -->
            <div class="relative overflow-hidden" x-data="{ isPaused: false }">
                <div class="flex gap-6 animate-marquee" 
                     x-bind:style="isPaused && 'animation-play-state: paused'"
                     @mouseenter="isPaused = true" 
                     @mouseleave="isPaused = false">
                    <!-- First Set -->
                    <?php foreach ($gameImages as $img): ?>
                    <div class="flex-shrink-0 w-80 h-48 rounded-2xl overflow-hidden border-2 border-border hover:border-primary/50 transition-all duration-300 hover:scale-105">
                        <img src="<?= $img ?>" alt="Gaming" class="w-full h-full object-cover">
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Duplicate for seamless loop -->
                    <?php foreach ($gameImages as $img): ?>
                    <div class="flex-shrink-0 w-80 h-48 rounded-2xl overflow-hidden border-2 border-border hover:border-primary/50 transition-all duration-300 hover:scale-105">
                        <img src="<?= $img ?>" alt="Gaming" class="w-full h-full object-cover">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-marquee {
    animation: marquee 30s linear infinite;
}
</style>
