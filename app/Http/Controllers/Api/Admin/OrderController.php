<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Models\Notificacion;
use App\Models\Pago;
use App\Models\Pedido;
use Flight;

/**
 * Gestión de pedidos/recargas para el panel admin (rol admin).
 * Reutiliza el presentador público de pedidos/pagos y replica las
 * transiciones y notificaciones del AdminController web, agregando la
 * actualización del estado del pago (antes nunca se actualizaba).
 */
final class OrderController
{
    private const ESTADOS = ['pendiente', 'confirmado', 'realizada', 'cancelado'];

    /** GET /api/admin/orders?estado=&search= — lista + contadores por estado. */
    public static function index(): void
    {
        $query = Flight::request()->query;
        $estado = $query->estado ?? null;
        $search = $query->search ?? null;

        if ($estado !== null && $estado !== '' && !in_array($estado, self::ESTADOS, true)) {
            Flight::json(['message' => 'Estado inválido.'], 422);
            return;
        }

        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->listarTodos([
            'estado' => $estado ?: null,
            'search' => $search,
        ]);

        Flight::json([
            'data' => array_map([ApiOrderController::class, 'present'], $pedidos),
            'contadores' => [
                'pendiente' => $pedidoModel->contarPorEstado('pendiente'),
                'confirmado' => $pedidoModel->contarPorEstado('confirmado'),
                'realizada' => $pedidoModel->contarPorEstado('realizada'),
                'cancelado' => $pedidoModel->contarPorEstado('cancelado'),
            ],
        ]);
    }

    /** GET /api/admin/orders/@id — detalle con pago y comprobante. */
    public static function show(string $id): void
    {
        $pedido = (new Pedido())->obtenerPorId($id);
        if (!$pedido) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        Flight::json([
            'data' => [
                'pedido' => ApiOrderController::present($pedido),
                'pago' => self::pagoFor((string) $id),
            ],
        ]);
    }

    /** POST /api/admin/orders/@id/verify — pendiente → confirmado (+ pago validado). */
    public static function verify(string $id): void
    {
        self::transition(
            $id,
            'confirmado',
            'validado',
            '✅ Pago Verificado',
            'Tu pago por el paquete {paquete} ha sido verificado. Estamos procesando tu recarga.',
        );
    }

    /** POST /api/admin/orders/@id/complete — confirmado → realizada. */
    public static function complete(string $id): void
    {
        self::transition(
            $id,
            'realizada',
            null,
            '💎 Recarga Completada',
            '¡Felicidades! Tu recarga de {paquete} ha sido enviada exitosamente. Revisa tu cuenta en el juego.',
        );
    }

    /** POST /api/admin/orders/@id/reject — pendiente → cancelado (+ pago rechazado). */
    public static function reject(string $id): void
    {
        self::transition(
            $id,
            'cancelado',
            'rechazado',
            '❌ Problema con el Pago',
            'No pudimos verificar tu pago para el paquete {paquete}. Por favor, contacta a soporte.',
        );
    }

    /** PUT /api/admin/orders/@id/estado — transición genérica del panel. */
    public static function updateEstado(string $id): void
    {
        $estado = trim((string) (Flight::request()->data->estado ?? ''));
        if (!in_array($estado, self::ESTADOS, true)) {
            Flight::json(['message' => 'Estado inválido. Usa pendiente, confirmado, realizada o cancelado.'], 422);
            return;
        }

        $pedidoModel = new Pedido();
        if (!$pedidoModel->obtenerPorId($id)) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        $pedidoModel->actualizarEstado($id, $estado);

        Flight::json([
            'data' => [
                'pedido' => ApiOrderController::present($pedidoModel->obtenerPorId($id)),
                'pago' => self::pagoFor($id),
            ],
        ]);
    }

    // ------------------------------------------------------------------

    private static function transition(
        string $id,
        string $nuevoEstado,
        ?string $pagoEstado,
        string $notificacionTitulo,
        string $notificacionMensaje,
    ): void {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);
        if (!$pedido) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        $pedidoModel->actualizarEstado($id, $nuevoEstado);

        $pagoModel = new Pago();
        $pago = $pagoModel->obtenerPorPedido($id);
        if ($pagoEstado !== null && $pago) {
            $pagoModel->actualizarEstado($pago['id'], $pagoEstado);
        }

        if (!empty($pedido['user_id'])) {
            (new Notificacion())->crear(
                $pedido['user_id'],
                $notificacionTitulo,
                str_replace('{paquete}', (string) $pedido['paquete'], $notificacionMensaje),
                'pedido_actualizado',
                '/notifications',
            );
        }

        Flight::json([
            'data' => [
                'pedido' => ApiOrderController::present($pedidoModel->obtenerPorId($id)),
                'pago' => self::pagoFor($id),
            ],
        ]);
    }

    private static function pagoFor(string $id): ?array
    {
        $pago = (new Pago())->obtenerPorPedido($id);

        return $pago ? ApiOrderController::presentPago($pago) : null;
    }
}
