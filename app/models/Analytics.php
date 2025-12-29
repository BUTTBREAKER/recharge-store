<?php

namespace RECHARGE\models;

class Analytics extends BaseModel
{
    /**
     * Obtiene ventas por hora del día actual
     */
    public function ventasDiarias()
    {
        $sql = "
            SELECT 
                HOUR(fecha) as hora,
                COUNT(*) as total_pedidos,
                SUM(monto) as total_ventas
            FROM pedidos
            WHERE DATE(fecha) = CURDATE()
            AND estado IN ('confirmado', 'realizada')
            GROUP BY HOUR(fecha)
            ORDER BY hora ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene ventas de los últimos 7 días
     */
    public function ventasSemanales()
    {
        $sql = "
            SELECT 
                DATE(fecha) as fecha,
                DAYNAME(fecha) as dia,
                COUNT(*) as total_pedidos,
                SUM(monto) as total_ventas
            FROM pedidos
            WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            AND estado IN ('confirmado', 'realizada')
            GROUP BY DATE(fecha)
            ORDER BY fecha ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene ventas del mes actual por día
     */
    public function ventasMensuales()
    {
        $sql = "
            SELECT 
                DAY(fecha) as dia,
                DATE(fecha) as fecha,
                COUNT(*) as total_pedidos,
                SUM(monto) as total_ventas
            FROM pedidos
            WHERE MONTH(fecha) = MONTH(CURDATE())
            AND YEAR(fecha) = YEAR(CURDATE())
            AND estado IN ('confirmado', 'realizada')
            GROUP BY DATE(fecha)
            ORDER BY fecha ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Resumen general de ventas
     */
    public function resumenVentas()
    {
        $sql = "
            SELECT 
                COUNT(*) as total_pedidos,
                SUM(CASE WHEN estado IN ('confirmado', 'realizada') THEN monto ELSE 0 END) as total_ventas,
                AVG(CASE WHEN estado IN ('confirmado', 'realizada') THEN monto ELSE NULL END) as promedio_venta,
                SUM(CASE WHEN DATE(fecha) = CURDATE() THEN 1 ELSE 0 END) as pedidos_hoy,
                SUM(CASE WHEN DATE(fecha) = CURDATE() AND estado IN ('confirmado', 'realizada') THEN monto ELSE 0 END) as ventas_hoy
            FROM pedidos
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    /**
     * Top productos más vendidos
     */
    public function topProductos($limit = 5)
    {
        $limit = intval($limit); // Sanitizar límite
        $sql = "
            SELECT 
                paquete,
                juego,
                COUNT(*) as total_ventas,
                SUM(monto) as total_monto
            FROM pedidos
            WHERE estado IN ('confirmado', 'realizada')
            GROUP BY paquete, juego
            ORDER BY total_ventas DESC
            LIMIT " . $limit . "
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Tasa de conversión (pedidos completados vs total)
     */
    public function tasaConversion()
    {
        $sql = "
            SELECT 
                COUNT(*) as total_pedidos,
                SUM(CASE WHEN estado IN ('confirmado', 'realizada') THEN 1 ELSE 0 END) as pedidos_completados,
                ROUND((SUM(CASE WHEN estado IN ('confirmado', 'realizada') THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as tasa_conversion
            FROM pedidos
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
