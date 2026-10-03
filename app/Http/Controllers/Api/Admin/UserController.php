<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Middleware\ApiAuth;
use App\Models\Pedido;
use App\Models\User;
use Flight;
use PDOException;

/**
 * Gestión de usuarios para el panel admin (rol admin).
 * Replica las capacidades del AdminController web (listado, rol, baja),
 * agregando el guard de no-autoeliminación y el 409 cuando el usuario
 * todavía tiene pedidos (FK pedidos.user_id → users).
 *
 * Nota de privacidad: igual que el web, el admin solo puede cambiar el
 * rol; name/email/password no son editables desde este módulo.
 */
final class UserController
{
    private const ROLES = ['admin', 'user'];

    /** GET /api/admin/users?rol= — lista + contadores por rol. */
    public static function index(): void
    {
        $rol = Flight::request()->query->rol ?? null;
        $rol = $rol !== null && $rol !== '' ? (string) $rol : null;

        if ($rol !== null && !in_array($rol, self::ROLES, true)) {
            Flight::json(['message' => 'Rol inválido. Usa admin o user.'], 422);
            return;
        }

        $userModel = new User();

        Flight::json([
            'data' => array_map(
                [self::class, 'present'],
                $userModel->listarTodos($rol),
            ),
            'contadores' => [
                'total' => $userModel->contarPorRol(),
                'admin' => $userModel->contarPorRol('admin'),
                'user' => $userModel->contarPorRol('user'),
            ],
        ]);
    }

    /** GET /api/admin/users/@id — detalle sin password + pedidos asociados. */
    public static function show(string $id): void
    {
        $user = new User()->obtenerPorId($id);
        if (!$user) {
            Flight::json(['message' => 'Usuario no encontrado.'], 404);
            return;
        }

        $pedidosCount = 0;
        try {
            $pedidosCount = count(new Pedido()->obtenerPorUsuario((int) $id));
        } catch (\Throwable) {
            // Si no se pueden contar los pedidos, el detalle sigue siendo útil con 0.
        }

        Flight::json([
            'data' => [
                'user' => self::present($user),
                'pedidos_count' => $pedidosCount,
            ],
        ]);
    }

    /** PUT /api/admin/users/@id/role — cambia rol (admin|user), nunca el propio. */
    public static function updateRole(string $id): void
    {
        $role = trim((string) (Flight::request()->data->role ?? ''));
        if (!in_array($role, self::ROLES, true)) {
            Flight::json(['message' => 'Rol inválido. Usa admin o user.'], 422);
            return;
        }

        $userModel = new User();
        $user = $userModel->obtenerPorId($id);
        if (!$user) {
            Flight::json(['message' => 'Usuario no encontrado.'], 404);
            return;
        }

        if (self::isSelf((string) $id)) {
            Flight::json([
                'message' => 'No puedes cambiar tu propio rol desde este módulo.',
            ], 403);
            return;
        }

        $userModel->cambiarRol($id, $role);

        Flight::json(['data' => self::present($userModel->obtenerPorId($id))]);
    }

    /** DELETE /api/admin/users/@id — baja del usuario (409 si tiene pedidos). */
    public static function destroy(string $id): void
    {
        $userModel = new User();
        $user = $userModel->obtenerPorId($id);
        if (!$user) {
            Flight::json(['message' => 'Usuario no encontrado.'], 404);
            return;
        }

        if (self::isSelf((string) $id)) {
            Flight::json([
                'message' => 'No puedes eliminar tu propia cuenta desde este módulo.',
            ], 403);
            return;
        }

        // La BD no impone FK sobre pedidos.user_id: la integridad se valida
        // aquí como regla de negocio (igual que la baja del admin web).
        try {
            $pedidosCount = count(new Pedido()->obtenerPorUsuario((int) $id));
        } catch (\Throwable) {
            $pedidosCount = 0;
        }
        if ($pedidosCount > 0) {
            Flight::json([
                'message' => 'No se puede eliminar: el usuario todavía tiene pedidos asociados.',
                'pedidos_count' => $pedidosCount,
            ], 409);
            return;
        }

        try {
            $userModel->eliminar($id);
        } catch (PDOException) {
            Flight::json([
                'message' => 'No se pudo eliminar el usuario.',
            ], 409);
            return;
        }

        Flight::json(['message' => 'Usuario eliminado.']);
    }

    // ------------------------------------------------------------------

    private static function isSelf(string $id): bool
    {
        $current = ApiAuth::user();

        // verify() devuelve el payload del token (uid/role/exp), no una fila
        // de usuario: la comparación es contra uid. En modo lenient puede no
        // haber identidad: sin token no hay "yo".
        return $current !== null && (string) ($current['uid'] ?? '') === $id;
    }

    private static function present(array $user): array
    {
        unset($user['password']);

        return $user;
    }
}
