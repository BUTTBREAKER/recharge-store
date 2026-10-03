<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Models\PaymentConfig;
use App\Models\SystemConfig;
use Flight;

/**
 * Configuración del sistema para el panel admin (rol admin):
 * datos de pago (Pago Móvil / Binance) y tasa de cambio USD→Bs.
 * Replica AdminController::managePayments/updatePaymentData y
 * updateExchangeRate en JSON.
 */
final class SettingsController
{
    private const METODOS = ['pagomovil', 'binance'];

    private const CAMPOS = [
        'pagomovil' => ['banco', 'telefono', 'cedula', 'titular'],
        'binance' => ['merchant_id', 'api_key', 'instrucciones'],
    ];

    /** GET /api/admin/payment-config — configuración de ambos métodos. */
    public static function paymentConfig(): void
    {
        Flight::json(['data' => self::metodos()]);
    }

    /** PUT /api/admin/payment-config — actualiza un método completo. */
    public static function updatePaymentConfig(): void
    {
        $data = Flight::request()->data;
        $metodo = trim((string) ($data->metodo ?? ''));

        if (!in_array($metodo, self::METODOS, true)) {
            Flight::json([
                'message' => 'Método inválido. Usa pagomovil o binance.',
            ], 422);
            return;
        }

        // Campos obligatorios: el modelo acepta ausentes con '', pero una
        // configuración de pago incompleta rompe el checkout, así que se
        // validan aquí explícitamente.
        $payload = [];
        foreach (self::CAMPOS[$metodo] as $campo) {
            $valor = trim((string) ($data->{$campo} ?? ''));
            if ($valor === '') {
                Flight::json([
                    'message' => "El campo {$campo} es obligatorio.",
                ], 422);
                return;
            }
            $payload[$campo] = $valor;
        }

        $paymentModel = new PaymentConfig();
        if ($metodo === 'pagomovil') {
            $paymentModel->actualizarPagoMovil($payload);
        } else {
            $paymentModel->actualizarBinance($payload);
        }

        Flight::json(['data' => self::metodos()]);
    }

    /** PUT /api/admin/exchange-rate — tasa de cambio USD → Bs. */
    public static function updateExchangeRate(): void
    {
        $rate = Flight::request()->data->exchange_rate_usd_bs ?? null;

        if (!is_numeric($rate) || (float) $rate <= 0) {
            Flight::json([
                'message' => 'La tasa de cambio debe ser un número mayor a 0.',
            ], 422);
            return;
        }

        new SystemConfig()->setExchangeRate((float) $rate);

        Flight::json([
            'data' => [
                'exchange_rate_usd_bs' => new SystemConfig()->getExchangeRate(),
            ],
        ]);
    }

    // ------------------------------------------------------------------

    /** Devuelve los métodos indexados por nombre con `config` ya decodificado. */
    private static function metodos(): array
    {
        $out = [];
        foreach (new PaymentConfig()->obtenerTodas() as $row) {
            $out[$row['metodo']] = [
                'id' => (int) $row['id'],
                'metodo' => $row['metodo'],
                'activo' => (bool) $row['activo'],
                'config' => $row['config_data'] ?? [],
                'updated_at' => $row['updated_at'] ?? null,
            ];
        }

        return $out;
    }
}
