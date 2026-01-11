<?php
/**
 * Script de Validación de Imágenes Requeridas
 * Verifica que todas las imágenes necesarias para el proyecto existan
 */

$requiredImages = [
    // Branding
    'public/logo.png' => 'Logo principal del sitio (light mode)',
    'public/logo-dark.png' => 'Logo para modo oscuro',
    'public/favicon.ico' => 'Favicon del sitio',
    
    // Mobile Legends
    'public/assets/images/games/mobile-legends/banner.jpg' => 'Banner principal de ML',
    'public/assets/images/games/mobile-legends/logo.png' => 'Logo oficial de Mobile Legends',
    'public/assets/images/games/mobile-legends/icon.png' => 'Icono cuadrado de ML',
    
    // Productos Mobile Legends
    'public/assets/images/games/mobile-legends/products/diamonds.png' => 'Icono de diamantes',
    'public/assets/images/games/mobile-legends/products/starlight.png' => 'Icono Starlight Member',
    'public/assets/images/games/mobile-legends/products/twilight.png' => 'Icono Twilight Pass',
    
    // Métodos de Pago
    'public/assets/images/payment-methods/pagomovil.png' => 'Logo Pago Móvil',
    'public/assets/images/payment-methods/binance.png' => 'Logo Binance Pay',
];

$optionalImages = [
    // UI Elements (opcionales pero recomendados)
    'public/assets/images/ui/hero-bg.jpg' => 'Background del hero section',
    'public/assets/images/ui/placeholder-avatar.png' => 'Avatar placeholder',
    
    // Badges
    'public/assets/images/badges/verified.svg' => 'Badge verificado',
    'public/assets/images/badges/secure.svg' => 'Badge seguro',
];

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║   Validación de Imágenes - Recharge Store             ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

$missing = [];
$found = 0;
$total = count($requiredImages);

echo "📋 Imágenes Requeridas:\n";
echo str_repeat("─", 60) . "\n\n";

foreach ($requiredImages as $path => $description) {
    if (file_exists($path)) {
        $size = filesize($path);
        $sizeKB = round($size / 1024, 2);
        echo "✅ {$description}\n";
        echo "   Ubicación: {$path}\n";
        echo "   Tamaño: {$sizeKB} KB\n\n";
        $found++;
    } else {
        echo "❌ {$description}\n";
        echo "   Ubicación esperada: {$path}\n";
        echo "   Estado: FALTANTE\n\n";
        $missing[] = [
            'path' => $path,
            'description' => $description
        ];
    }
}

echo str_repeat("─", 60) . "\n";
echo "Resultado: {$found}/{$total} imágenes requeridas encontradas\n";
echo str_repeat("─", 60) . "\n\n";

// Imágenes opcionales
echo "📋 Imágenes Opcionales (Recomendadas):\n";
echo str_repeat("─", 60) . "\n\n";

$optionalFound = 0;
foreach ($optionalImages as $path => $description) {
    if (file_exists($path)) {
        echo "✅ {$description}\n";
        echo "   Ubicación: {$path}\n\n";
        $optionalFound++;
    } else {
        echo "⚠️  {$description}\n";
        echo "   Ubicación esperada: {$path}\n\n";
    }
}

echo str_repeat("─", 60) . "\n";
echo "Opcionales: {$optionalFound}/" . count($optionalImages) . " encontradas\n";
echo str_repeat("─", 60) . "\n\n";

// Resumen final
echo "╔════════════════════════════════════════════════════════╗\n";
echo "║                    RESUMEN FINAL                       ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

if (count($missing) > 0) {
    echo "⚠️  ACCIÓN REQUERIDA: Faltan " . count($missing) . " imágenes críticas\n\n";
    echo "Imágenes faltantes:\n";
    foreach ($missing as $img) {
        echo "  • {$img['description']}\n";
        echo "    → {$img['path']}\n\n";
    }
    
    echo "\n📖 Consulta guia_imagenes.md para detalles de cada imagen\n";
    echo "🔧 Ejecuta setup-images.bat para crear los directorios\n\n";
    
    exit(1);
} else {
    echo "✅ ¡EXCELENTE! Todas las imágenes requeridas están presentes\n\n";
    
    if ($optionalFound < count($optionalImages)) {
        echo "💡 Tip: Considera agregar las imágenes opcionales para mejorar la UX\n\n";
    }
    
    exit(0);
}
