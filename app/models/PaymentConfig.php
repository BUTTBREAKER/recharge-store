<?php
class PaymentConfig {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Obtiene configuración de un método de pago
     */
    public function obtenerConfig($metodo) {
        $stmt = $this->db->prepare("SELECT * FROM payment_config WHERE metodo = ?");
        $stmt->execute([$metodo]);
        $result = $stmt->fetch();
        
        if ($result && isset($result['config'])) {
            $result['config_data'] = json_decode($result['config'], true);
        }
        
        return $result;
    }

    /**
     * Obtiene todas las configuraciones
     */
    public function obtenerTodas() {
        $stmt = $this->db->query("SELECT * FROM payment_config");
        $results = $stmt->fetchAll();
        
        foreach ($results as &$result) {
            if (isset($result['config'])) {
                $result['config_data'] = json_decode($result['config'], true);
            }
        }
        
        return $results;
    }

    /**
     * Actualiza configuración de Pago Móvil
     */
    public function actualizarPagoMovil($datos) {
        $config = json_encode([
            'banco' => $datos['banco'] ?? '',
            'telefono' => $datos['telefono'] ?? '',
            'cedula' => $datos['cedula'] ?? '',
            'titular' => $datos['titular'] ?? ''
        ]);

        $stmt = $this->db->prepare("
            UPDATE payment_config 
            SET config = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE metodo = 'pagomovil'
        ");
        return $stmt->execute([$config]);
    }

    /**
     * Actualiza configuración de Binance
     */
    public function actualizarBinance($datos) {
        $config = json_encode([
            'merchant_id' => $datos['merchant_id'] ?? '',
            'api_key' => $datos['api_key'] ?? '',
            'instrucciones' => $datos['instrucciones'] ?? ''
        ]);

        $stmt = $this->db->prepare("
            UPDATE payment_config 
            SET config = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE metodo = 'binance'
        ");
        return $stmt->execute([$config]);
    }

    /**
     * Toggle activo/inactivo de un método de pago
     */
    public function toggleActivo($metodo) {
        $stmt = $this->db->prepare("UPDATE payment_config SET activo = NOT activo WHERE metodo = ?");
        return $stmt->execute([$metodo]);
    }
}
