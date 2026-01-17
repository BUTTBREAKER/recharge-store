<?php

use flight\Container;
use Leaf\Db;
use Symfony\Component\String\Slugger\SluggerInterface;

$db = Container::getInstance()->get(Db::class);

$games = $db->query('select distinct juego as game from productos')->fetchAll() ?: [];
$slug = Container::getInstance()->get(SluggerInterface::class)->slug(...);

?>

<div id="games" class="py-12">
    <div class="text-center mb-12 animate-fade-in">
        <h2 class="text-3xl font-extrabold text-foreground">
            Juegos Disponibles
        </h2>
        <p class="mt-4 max-w-2xl text-xl text-muted-foreground mx-auto">
            Selecciona tu juego para ver las ofertas exclusivas.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($games as $game) : ?>
            <div class="group relative bg-card border-2 border-border hover:border-primary/50 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-primary/20 hover:scale-105">
                <!-- Image Container -->
                <div class="relative h-48 w-full overflow-hidden bg-gradient-to-br from-primary/10 to-accent/10">
                    <?php
                    $gameSlug = $slug($game['game']);
                    $iconPath = "assets/images/games/{$gameSlug}/icon.png";
                    $hasIcon = file_exists(__DIR__ . '/../../../public/' . $iconPath);
                    ?>
                    <?php if ($hasIcon) : ?>
                        <img 
                            src="/<?= $iconPath ?>" 
                            alt="<?= $game['game'] ?>" 
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                        >
                    <?php else : ?>
                        <div class="flex items-center justify-center h-full bg-gradient-to-br from-primary to-accent">
                            <span class="text-7xl drop-shadow-lg filter group-hover:scale-110 transition-transform duration-300">🎮</span>
                        </div>
                    <?php endif; ?>
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-background/90 via-background/20 to-transparent"></div>
                    
                    <!-- Popular Badge (for first game) -->
                    <?php if ($games[0] === $game): ?>
                    <div class="absolute top-3 right-3 z-10 bg-gradient-to-r from-secondary to-amber-400 text-secondary-foreground px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        Popular
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="p-6 space-y-3">
                    <h3 class="text-xl font-bold text-foreground group-hover:text-primary transition-colors">
                        <?= $game['game'] ?>
                    </h3>
                    <p class="text-sm text-muted-foreground uppercase tracking-wide font-semibold">
                        <?= $game['developer'] ?? 'Moonton' ?>
                    </p>
                    <p class="text-muted-foreground line-clamp-2 leading-relaxed">
                        Recarga diamantes directos a tu ID. Proceso 100% seguro y automático.
                    </p>

                    <a
                        href="./juego/<?= $slug($game['game']) ?>"
                        class="block w-full text-center bg-gradient-to-r from-primary to-accent text-primary-foreground font-bold py-3 px-6 rounded-xl hover:opacity-90 transition-all duration-300 shadow-lg hover:shadow-xl mt-4">
                        Ver Paquetes →
                    </a>
                </div>
                
                <!-- Glow Effect -->
                <div class="absolute inset-0 -z-10 bg-gradient-to-r from-primary/20 to-accent/20 opacity-0 group-hover:opacity-100 blur-xl transition-opacity duration-300"></div>
            </div>
        <?php endforeach ?>

        <!-- Coming Soon Card -->
        <div class="relative bg-muted/50 rounded-2xl border-2 border-dashed border-border p-8 flex flex-col items-center justify-center text-center opacity-75">
            <span class="text-5xl mb-4 grayscale">🎮</span>
            <h3 class="text-xl font-bold text-muted-foreground">Próximamente</h3>
            <p class="text-muted-foreground mt-2">Free Fire, PUBG y más...</p>
        </div>
    </div>
</div>
