<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ApiAuth;
use App\Models\Pago;
use App\Models\PaymentConfig;
use App\Models\Pedido;
use App\Validators\PlayerIdValidator;
use Flight;

/**
 * Pedidos de la API (checkout + estado de pago).
 * Los endpoints son públicos con identidad opcional: el checkout permite
 * invitados; si el cliente envía un Bearer válido se asocia el pedido al
 * usuario (mismo comportamiento que la sesión en el sitio web).
 */
final class OrderController
{
    /** POST /api/orders — crea el pedido (checkout). */
    public static function create(): void
    {
        $data = Flight::request()->data;
        $playerId = trim((string) ($data->player_id ?? ''));
        $serverId = trim((string) ($data->server_id ?? ''));
        $paquete = trim((string) ($data->paquete ?? ''));
        $monto = (string) ($data->monto ?? '');

        $validation = PlayerIdValidator::validate($playerId, $serverId);
        if (!$validation['success']) {
            Flight::json(['message' => $validation['message']], 422);
            return;
        }
        if ($paquete === '' || !is_numeric($monto) || (float) $monto <= 0) {
            Flight::json(['message' => 'Paquete y monto son obligatorios.'], 422);
            return;
        }

        $pedidoData = [
            'juego' => trim((string) ($data->juego ?? '')),
            'player_id' => $playerId,
            'server_id' => $serverId,
            'paquete' => $paquete,
            'monto' => (float) $monto,
            // Temporal: se define en POST /api/orders/{id}/pay.
            'metodo_pago' => 'pagomovil',
            'telefono' => trim((string) ($data->telefono ?? '')),
        ];

        $user = ApiAuth::user();
        if ($user !== null) {
            $pedidoData['user_id'] = (int) $user['uid'];
        }

        $pedidoModel = new Pedido();
        $pedidoId = $pedidoModel->crear($pedidoData);

        Flight::json(['data' => self::present($pedidoModel->obtenerPorId($pedidoId))], 201);
    }

    /** POST /api/orders/@id/pay — define el método de pago y devuelve los datos para pagar. */
    public static function pay(string $id): void
    {
        $metodo = trim((string) (Flight::request()->data->metodo ?? ''));
        if (!in_array($metodo, ['pagomovil', 'binance'], true)) {
            Flight::json(['message' => 'Método de pago inválido. Usa pagomovil o binance.'], 422);
            return;
        }

        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);
        if (!$pedido) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        $pedidoModel->actualizarMetodoPago($id, $metodo);
        $pedido = $pedidoModel->obtenerPorId($id);

        $payload = [
            'pedido' => self::present($pedido),
            'metodo' => $metodo,
        ];

        if ($metodo === 'pagomovil') {
            $config = (new PaymentConfig())->obtenerConfig('pagomovil');
            $payload['beneficiary'] = $config['config_data'] ?? null;
        } else {
            $payload['binance'] = ['url' => self::binanceUrl((string) $id)];
        }

        Flight::json(['data' => $payload]);
    }

    /** GET /api/orders/@id/status — estado del pedido y su pago. */
    public static function status(string $id): void
    {
        $pedido = (new Pedido())->obtenerPorId($id);
        if (!$pedido) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        $pago = (new Pago())->obtenerPorPedido($id);

        Flight::json([
            'data' => [
                'pedido' => self::present($pedido),
                'pago' => $pago ? self::presentPago($pago) : null,
            ],
        ]);
    }

    /** GET /api/orders/@id/binance — link simulado de Binance Pay (mismo que el sitio). */
    public static function binance(string $id): void
    {
        $pedido = (new Pedido())->obtenerPorId($id);
        if (!$pedido) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        Flight::json([
            'data' => [
                'pedido' => self::present($pedido),
                'binance' => ['url' => self::binanceUrl($id)],
            ],
        ]);
    }

    private static function binanceUrl(string $id): string
    {
        // Simulación: aquí iría la integración real con Binance Pay API.
        return 'https://pay.binance.com/checkout/simulado_' . $id;
    }

    // ------------------------------------------------------------------
    // Presentación
    // ------------------------------------------------------------------

    /** Presenta un pedido (reutilizado por ProfileController). */
    public static function present(array $pedido): array
    {
        return [
            'id' => (int) $pedido['id'],
            'user_id' => isset($pedido['user_id']) && $pedido['user_id'] !== null
                ? (int) $pedido['user_id']
                : null,
            'juego' => $pedido['juego'],
            'player_id' => $pedido['player_id'],
            'server_id' => $pedido['server_id'],
            'paquete' => $pedido['paquete'],
            'monto' => (float) $pedido['monto'],
            'metodo_pago' => $pedido['metodo_pago'],
            'estado' => $pedido['estado'],
            'telefono' => $pedido['telefono'],
            'fecha' => $pedido['fecha'],
        ];
    }

    private static function presentPago(array $pago): array
    {
        $comprobante = (string) ($pago['comprobante'] ?? '');
        // Registros viejos del sitio web guardan 'uploads/...' (relativo);
        // normalizamos a ruta absoluta para que el frontend pueda cargarla.
        if ($comprobante !== '' && !str_starts_with($comprobante, '/')) {
            $comprobante = '/' . ltrim($comprobante, '/');
        }

        return [
            'id' => (int) $pago['id'],
            'pedido_id' => (int) $pago['pedido_id'],
            'referencia' => $pago['referencia'],
            'comprobante' => $comprobante !== '' ? $comprobante : null,
            'estado' => $pago['estado'],
            'provider' => $pago['provider'],
        ];
    }
}
