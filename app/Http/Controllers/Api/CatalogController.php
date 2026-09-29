<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Juego;
use App\Models\Producto;
use App\Models\SystemConfig;
use Flight;

/**
 * Catálogo público de la API: juegos, paquetes y tasa de cambio.
 * Reutiliza los mismos modelos que renderiza el sitio web.
 */
final class CatalogController
{
    /** GET /api/games */
    public static function games(): void
    {
        $games = (new Juego())->listarTodos(true);

        Flight::json(['data' => array_map([self::class, 'presentGame'], $games)]);
    }

    /** GET /api/games/@slug */
    public static function game(string $slug): void
    {
        $game = (new Juego())->obtenerPorSlug($slug);
        if (!$game || empty($game['activo'])) {
            Flight::json(['message' => 'Juego no encontrado.'], 404);
            return;
        }

        Flight::json(['data' => self::presentGame($game)]);
    }

    /** GET /api/games/@slug/products */
    public static function products(string $slug): void
    {
        $game = (new Juego())->obtenerPorSlug($slug);
        if (!$game || empty($game['activo'])) {
            Flight::json(['message' => 'Juego no encontrado.'], 404);
            return;
        }

        $products = (new Producto())->obtenerPorJuego($game['nombre']);
        Flight::json(['data' => array_map([self::class, 'presentProduct'], $products)]);
    }

    /** GET /api/config/exchange-rate */
    public static function exchangeRate(): void
    {
        Flight::json([
            'data' => ['exchange_rate_usd_bs' => (new SystemConfig())->getExchangeRate()],
        ]);
    }

    // ------------------------------------------------------------------
    // Presentación (solo campos públicos)
    // ------------------------------------------------------------------

    private static function presentGame(array $game): array
    {
        return [
            'id' => (int) $game['id'],
            'nombre' => $game['nombre'],
            'slug' => $game['slug'],
            'descripcion' => $game['descripcion'],
            'imagen' => $game['imagen'],
            'icono' => $game['icono'],
            'orden' => (int) $game['orden'],
        ];
    }

    private static function presentProduct(array $product): array
    {
        return [
            'id' => (int) $product['id'],
            'nombre' => $product['nombre'],
            'cantidad' => (int) $product['cantidad'],
            'precio' => (float) $product['precio'],
            'precio_original' => $product['precio_original'] !== null
                ? (float) $product['precio_original']
                : null,
            'orden' => (int) $product['orden'],
        ];
    }
}
