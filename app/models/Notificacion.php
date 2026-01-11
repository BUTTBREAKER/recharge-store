<?php

namespace App\Models;

class Notificacion extends BaseModel
{
    /**
     * Crear una nueva notificación
     */
    public function crear($userId, $titulo, $mensaje, $tipo = 'pedido_actualizado', $link = null)
    {
        $sql = "INSERT INTO notificaciones (user_id, titulo, mensaje, tipo, link) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $titulo, $mensaje, $tipo, $link]);
    }

    /**
     * Obtener todas las notificaciones de un usuario
     */
    public function obtenerPorUsuario($userId, $limit = 20)
    {
        $sql = "SELECT * FROM notificaciones 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT " . intval($limit);
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Contar notificaciones sin leer de un usuario
     */
    public function contarSinLeer($userId)
    {
        $sql = "SELECT COUNT(*) as total FROM notificaciones 
                WHERE user_id = ? AND leido = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasComoLeidas($userId)
    {
        $sql = "UPDATE notificaciones SET leido = 1 WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }
    
    /**
     * Eliminar notificaciones antiguas (opcional)
     */
    public function limpiarAntiguas($userId, $dias = 30)
    {
        $sql = "DELETE FROM notificaciones WHERE user_id = ? AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $dias]);
    }
}
