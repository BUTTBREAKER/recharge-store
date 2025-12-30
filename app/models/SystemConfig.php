<?php

namespace App\Models;

use PDOException;

class SystemConfig extends BaseModel
{
    /**
     * Obtener configuración por clave
     */
    public function get($key)
    {
        try {
            $stmt = $this->db->prepare("SELECT config_value FROM system_config WHERE config_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            return $result['config_value'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Actualizar configuración
     */
    public function set($key, $value, $description = null)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO system_config (config_key, config_value, description) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE config_value = ?, updated_at = CURRENT_TIMESTAMP
            ");
            return $stmt->execute([$key, $value, $description, $value]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtener tasa de cambio USD a Bolívares
     */
    public function getExchangeRate()
    {
        $rate = $this->get('exchange_rate_usd_bs');
        return $rate ? floatval($rate) : 36.50; // Default 36.50 Bs
    }

    /**
     * Actualizar tasa de cambio
     */
    public function setExchangeRate($rate)
    {
        return $this->set('exchange_rate_usd_bs', $rate, 'Tasa de cambio USD a Bolívares para Pago Móvil');
    }

    /**
     * Convertir USD a Bolívares
     */
    public function convertUsdToBs($usd)
    {
        $rate = $this->getExchangeRate();
        return round($usd * $rate, 2);
    }
}
