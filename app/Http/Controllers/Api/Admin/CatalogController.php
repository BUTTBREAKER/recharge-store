<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Models\Juego;
use App\Models\Producto;
use Flight;

/**
 * CRUD de productos y juegos para el panel admin (rol admin),
 * incluida la gestión de precios y los toggles de activo.
 */
final class CatalogController
{
    // ------------------------------------------------------------------
    // Productos
    // ------------------------------------------------------------------

    /** GET /api/admin/products?juego= — incluye inactivos. */
    public static function products(): void
    {
        $juego = Flight::request()->query->juego ?? null;

        $productos = (new Producto())->listarTodos($juego ?: null, false);

        Flight::json(['data' => array_map([self::class, 'presentProduct'], $productos)]);
    }

    /** POST /api/admin/products */
    public static function productStore(): void
    {
        $payload = self::productPayload(Flight::request()->data);

        $newId = (new Producto())->crear($payload);

        Flight::json(['data' => self::presentProduct((new Producto())->obtenerPorId($newId))], 201);
    }

    /** PUT /api/admin/products/@id */
    public static function productUpdate(string $id): void
    {
        $productoModel = new Producto();
        $producto = $productoModel->obtenerPorId($id);
        if (!$producto) {
            Flight::json(['message' => 'Producto no encontrado.'], 404);
            return;
        }

        $data = Flight::request()->data;
        $payload = self::productPayload($data, $producto);

        $productoModel->actualizar($id, $payload);

        Flight::json(['data' => self::presentProduct($productoModel->obtenerPorId($id))]);
    }

    /** DELETE /api/admin/products/@id */
    public static function productDelete(string $id): void
    {
        $productoModel = new Producto();
        if (!$productoModel->obtenerPorId($id)) {
            Flight::json(['message' => 'Producto no encontrado.'], 404);
            return;
        }

        $productoModel->eliminar($id);

        Flight::json(['message' => 'Producto eliminado.']);
    }

    /** PUT /api/admin/products/@id/price */
    public static function productUpdatePrice(string $id): void
    {
        $precio = Flight::request()->data->precio ?? null;

        if (!is_numeric($precio) || (float) $precio <= 0) {
            Flight::json(['message' => 'El precio debe ser un número mayor a 0.'], 422);
            return;
        }

        $productoModel = new Producto();
        if (!$productoModel->obtenerPorId($id)) {
            Flight::json(['message' => 'Producto no encontrado.'], 404);
            return;
        }

        $productoModel->actualizarPrecio($id, (float) $precio);

        Flight::json(['data' => self::presentProduct($productoModel->obtenerPorId($id))]);
    }

    /** POST /api/admin/products/@id/toggle */
    public static function productToggle(string $id): void
    {
        $productoModel = new Producto();
        if (!$productoModel->obtenerPorId($id)) {
            Flight::json(['message' => 'Producto no encontrado.'], 404);
            return;
        }

        $productoModel->toggleActivo($id);

        Flight::json(['data' => self::presentProduct($productoModel->obtenerPorId($id))]);
    }

    // ------------------------------------------------------------------
    // Juegos
    // ------------------------------------------------------------------

    /** GET /api/admin/games — incluye inactivos (también alimenta el select del form de productos). */
    public static function games(): void
    {
        $juegos = (new Juego())->listarTodos(false);

        Flight::json(['data' => array_map([self::class, 'presentGame'], $juegos)]);
    }

    /** POST /api/admin/games */
    public static function gameStore(): void
    {
        $data = Flight::request()->data;
        $nombre = trim((string) ($data->nombre ?? ''));

        if ($nombre === '') {
            Flight::json(['message' => 'El nombre es obligatorio.'], 422);
            return;
        }

        $slug = trim((string) ($data->slug ?? ''));
        $finalSlug = $slug !== '' ? $slug : Juego::generarSlug($nombre);

        $newId = (new Juego())->crear([
            'nombre' => $nombre,
            'slug' => $finalSlug,
            'descripcion' => ($data->descripcion ?? '') !== '' ? $data->descripcion : null,
            'imagen' => ($data->imagen ?? '') !== '' ? $data->imagen : null,
            'icono' => ($data->icono ?? '') !== '' ? $data->icono : '🎮',
            'orden' => is_numeric($data->orden ?? null) ? (int) $data->orden : 0,
            'activo' => (int) self::activoFlag($data, true),
        ]);

        Flight::json(['data' => self::presentGame((new Juego())->obtenerPorId($newId))], 201);
    }

    /** PUT /api/admin/games/@id */
    public static function gameUpdate(string $id): void
    {
        $juegoModel = new Juego();
        $juego = $juegoModel->obtenerPorId($id);
        if (!$juego) {
            Flight::json(['message' => 'Juego no encontrado.'], 404);
            return;
        }

        $data = Flight::request()->data;
        $nombre = trim((string) ($data->nombre ?? $juego['nombre']));

        if ($nombre === '') {
            Flight::json(['message' => 'El nombre es obligatorio.'], 422);
            return;
        }

        $juegoModel->actualizar($id, [
            'nombre' => $nombre,
            'slug' => trim((string) ($data->slug ?? $juego['slug'])),
            'descripcion' => (($data->descripcion ?? '') !== '' ? $data->descripcion : null),
            'imagen' => (($data->imagen ?? '') !== '' ? $data->imagen : null),
            'icono' => (($data->icono ?? '') !== '' ? $data->icono : '🎮'),
            'orden' => is_numeric($data->orden ?? null) ? (int) $data->orden : (int) $juego['orden'],
            'activo' => (int) self::activoFlag($data, (bool) $juego['activo']),
        ]);

        Flight::json(['data' => self::presentGame($juegoModel->obtenerPorId($id))]);
    }

    /** DELETE /api/admin/games/@id */
    public static function gameDelete(string $id): void
    {
        $juegoModel = new Juego();
        if (!$juegoModel->obtenerPorId($id)) {
            Flight::json(['message' => 'Juego no encontrado.'], 404);
            return;
        }

        $juegoModel->eliminar($id);

        Flight::json(['message' => 'Juego eliminado.']);
    }

    /** POST /api/admin/games/@id/toggle */
    public static function gameToggle(string $id): void
    {
        $juegoModel = new Juego();
        if (!$juegoModel->obtenerPorId($id)) {
            Flight::json(['message' => 'Juego no encontrado.'], 404);
            return;
        }

        $juegoModel->toggleActivo($id);

        Flight::json(['data' => self::presentGame($juegoModel->obtenerPorId($id))]);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Valida y arma el payload de producto. $actual permite preservar
     * campos no enviados en PUT (parcial) y el estado actual de activo.
     */
    private static function productPayload(object $data, ?array $actual = null): array
    {
        $nombre = trim((string) ($data->nombre ?? $actual['nombre'] ?? ''));
        $juego = trim((string) ($data->juego ?? $actual['juego'] ?? ''));
        $precio = $data->precio ?? $actual['precio'] ?? null;

        if ($nombre === '' || $juego === '') {
            Flight::jsonHalt(['message' => 'Nombre y juego son obligatorios.'], 422);
        }
        if (!is_numeric($precio) || (float) $precio < 0) {
            Flight::jsonHalt(['message' => 'El precio debe ser un número mayor o igual a 0.'], 422);
        }

        $precioOriginal = $data->precio_original ?? $actual['precio_original'] ?? null;

        return [
            'juego' => $juego,
            'nombre' => $nombre,
            'cantidad' => (int) ($data->cantidad ?? $actual['cantidad'] ?? 0),
            'precio' => (float) $precio,
            'precio_original' => is_numeric($precioOriginal) ? (float) $precioOriginal : null,
            'orden' => is_numeric($data->orden ?? null) ? (int) $data->orden : (int) ($actual['orden'] ?? 0),
            'activo' => (int) self::activoFlag($data, $actual !== null ? (bool) $actual['activo'] : true),
        ];
    }

    /**
     * `activo` booleano del cuerpo JSON; en PUT omite = conserva valor actual.
     * Nota: request()->data es un objeto con acceso mágico (no stdClass),
     * por lo que no sirve property_exists; se usa ?? que invoca __isset.
     */
    private static function activoFlag(object $data, bool $default): bool
    {
        $value = $data->activo ?? null;
        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private static function presentProduct(array $product): array
    {
        return [
            'id' => (int) $product['id'],
            'juego' => $product['juego'],
            'nombre' => $product['nombre'],
            'cantidad' => (int) $product['cantidad'],
            'precio' => (float) $product['precio'],
            'precio_original' => $product['precio_original'] !== null ? (float) $product['precio_original'] : null,
            'orden' => (int) $product['orden'],
            'activo' => (bool) $product['activo'],
        ];
    }

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
            'activo' => (bool) $game['activo'],
        ];
    }
}
