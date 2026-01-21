<?php

namespace App\Models;

use PDOException;

class Pedido extends BaseModel
{
    public function crear($data)
    {
        $hasUserId = isset($data['user_id']);
        $sql = "INSERT INTO pedidos (juego, player_id, server_id, paquete, monto, metodo_pago, telefono, estado" . ($hasUserId ? ", user_id" : "") . ") 
                VALUES (:juego, :player_id, :server_id, :paquete, :monto, :metodo_pago, :telefono, 'pendiente'" . ($hasUserId ? ", :user_id" : "") . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizarEstado($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function listarTodos($filters = [], $limit = null)
    {
        $sql = "SELECT * FROM pedidos WHERE 1=1";
        $params = [];

        if (!empty($filters['estado'])) {
            $sql .= " AND estado = :estado";
            $params['estado'] = $filters['estado'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (id LIKE :search OR player_id LIKE :search OR telefono LIKE :search OR comprobante LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY fecha DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Contar pedidos por estado
     */
    public function contarPorEstado($estado)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pedidos WHERE estado = ?");
        $stmt->execute([$estado]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Obtener pedidos de un usuario específico
     * Nota: requiere que la columna user_id exista en pedidos
     */
    public function obtenerPorUsuario($userId, $limit = null, $filters = [])
    {
        // Verificar si la columna user_id existe
        try {
            $sql = "SELECT * FROM pedidos WHERE user_id = :user_id";
            $params = ['user_id' => $userId];

            if (!empty($filters['search'])) {
                $sql .= " AND (id LIKE :search OR player_id LIKE :search OR paquete LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }

            if (!empty($filters['estado'])) {
                $sql .= " AND estado = :estado";
                $params['estado'] = $filters['estado'];
            }

            $sql .= " ORDER BY fecha DESC";
            
            if ($limit) {
                $sql .= " LIMIT " . intval($limit);
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Si la columna no existe, retornar array vacío
            return [];
        }
    }

    /**
     * Contar pedidos activos de un usuario (pendiente + confirmado)
     */
    public function contarActivosPorUsuario($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pedidos WHERE user_id = ? AND estado IN ('pendiente', 'confirmado')");
            $stmt->execute([$userId]);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            // Si la columna no existe, retornar 0
            return 0;
        }
    }
}
