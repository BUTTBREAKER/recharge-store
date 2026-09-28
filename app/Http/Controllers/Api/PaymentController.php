<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Pago;
use App\Models\Pedido;
use Flight;

/**
 * Confirmación de pago Móvil de la API: recibe el multipart con la
 * referencia y el comprobante, lo guarda en public/uploads/ (creando el
 * directorio si no existe) y registra el pago con estado 'pendiente'.
 */
final class PaymentController
{
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    /** POST /api/payments/pagomovil — multipart: pedido_id, referencia, comprobante. */
    public static function confirmPagomovil(): void
    {
        $data = Flight::request()->data;
        $files = Flight::request()->files;

        $pedidoId = (int) ($data->pedido_id ?? 0);
        $referencia = trim((string) ($data->referencia ?? ''));

        if ($pedidoId <= 0 || $referencia === '') {
            Flight::json(['message' => 'pedido_id y referencia son obligatorios.'], 422);
            return;
        }
        if (!(new Pedido())->obtenerPorId($pedidoId)) {
            Flight::json(['message' => 'Pedido no encontrado.'], 404);
            return;
        }

        $comprobantePath = '';
        if (isset($files['comprobante']) && $files['comprobante']['error'] !== UPLOAD_ERR_NO_FILE) {
            $comprobantePath = self::storeComprobante($pedidoId, $files['comprobante']);
        }

        (new Pago())->registrar([
            'pedido_id' => $pedidoId,
            'referencia' => $referencia,
            'comprobante' => $comprobantePath,
            'provider' => 'pagomovil',
        ]);

        Flight::json(
            [
                'data' => [
                    'pedido_id' => $pedidoId,
                    'referencia' => $referencia,
                    'comprobante' => $comprobantePath !== '' ? $comprobantePath : null,
                    'estado' => 'pendiente',
                ],
            ],
            201,
        );
    }

    /**
     * Valida y mueve el archivo subido a public/uploads/ con ruta absoluta
     * (/uploads/...), que es lo que el frontend Next.js puede cargar tal cual.
     *
     * @param array{name: string, tmp_name: string, error: int, size: int} $file
     */
    private static function storeComprobante(int $pedidoId, array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Flight::jsonHalt(['message' => 'Error al subir el comprobante (código ' . $file['error'] . ').'], 422);
        }

        $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            Flight::jsonHalt(['message' => 'Formato de comprobante no permitido. Usa JPG, PNG, WEBP o PDF.'], 422);
        }
        if ($file['size'] > self::MAX_FILE_SIZE) {
            Flight::jsonHalt(['message' => 'El comprobante supera el máximo de 5 MB.'], 422);
        }

        $directory = dirname(__DIR__, 4) . '/public/uploads';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            Flight::jsonHalt(['message' => 'No se pudo crear el directorio de subidas.'], 500);
        }

        $filename = 'comp_' . $pedidoId . '_' . time() . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
            Flight::jsonHalt(['message' => 'No se pudo guardar el comprobante.'], 500);
        }

        return '/uploads/' . $filename;
    }
}
