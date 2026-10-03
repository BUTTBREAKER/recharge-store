<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ApiAuth;
use App\Models\Notificacion;
use Flight;

/**
 * Notificaciones del usuario autenticado (Bearer).
 */
final class NotificationController
{
    /** GET /api/notifications — últimas 20 + contador de no leídas. */
    public static function index(): void
    {
        $auth = ApiAuth::requireUser();

        $model = new Notificacion();
        $notifications = $model->obtenerPorUsuario($auth['uid'], 20);

        Flight::json([
            'data' => array_map([self::class, 'present'], $notifications),
            'unread' => $model->contarSinLeer($auth['uid']),
        ]);
    }

    /** POST /api/notifications/read-all */
    public static function readAll(): void
    {
        $auth = ApiAuth::requireUser();

        new Notificacion()->marcarTodasComoLeidas($auth['uid']);

        Flight::json(['message' => 'Notificaciones marcadas como leídas.']);
    }

    private static function present(array $notification): array
    {
        return [
            'id' => (int) $notification['id'],
            'titulo' => $notification['titulo'],
            'mensaje' => $notification['mensaje'],
            'tipo' => $notification['tipo'],
            'link' => $notification['link'],
            'leido' => (bool) $notification['leido'],
            'created_at' => $notification['created_at'],
        ];
    }
}
