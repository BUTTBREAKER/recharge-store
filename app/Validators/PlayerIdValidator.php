<?php

namespace App\Validators;

class PlayerIdValidator
{
    /**
     * Valida el formato de Player ID y Server ID para Mobile Legends
     * Player ID suele ser numérico (8-10 dígitos)
     * Server ID suele ser numérico (4-5 dígitos)
     *
     * @param string|int $playerId
     * @param string|int $serverId
     * @return array ['success' => bool, 'message' => string]
     */
    public static function validate($playerId, $serverId): array
    {
        // Limpiar espacios
        $playerId = trim($playerId);
        $serverId = trim($serverId);

        if (empty($playerId)) {
            return ['success' => false, 'message' => 'El Player ID es obligatorio.'];
        }

        if (empty($serverId)) {
            return ['success' => false, 'message' => 'El Server ID es obligatorio.'];
        }

        // Validación de Player ID (Solo números)
        if (!preg_match('/^[0-9]+$/', $playerId)) {
            return ['success' => false, 'message' => 'El Player ID debe contener solo números.'];
        }

        // Longitud típica de ML Player ID (8 a 13 dígitos actualmente)
        if (strlen($playerId) < 6 || strlen($playerId) > 13) {
            return ['success' => false, 'message' => 'El Player ID parece inválido (longitud incorrecta).'];
        }

        // Validación de Server ID (Solo números)
        if (!preg_match('/^[0-9]+$/', $serverId)) {
            return ['success' => false, 'message' => 'El Server ID debe contener solo números.'];
        }

        // Longitud típica de ML Server ID (3 a 6 dígitos)
        if (strlen($serverId) < 3 || strlen($serverId) > 6) {
            return ['success' => false, 'message' => 'El Server ID parece inválido (longitud incorrecta).'];
        }

        return ['success' => true, 'message' => 'Validación exitosa.'];
    }
}
