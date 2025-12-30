<?php

namespace App\Models;

use PDOException;

class Pedido extends BaseModel
{
    public function crear($data)
    {
        $sql = "INSERT INTO pedidos (juego, player_id, server_id, paquete, monto, metodo_pago, telefono, estado) 
                VALUES (:juego, :player_id, :server_id, :paquete, :monto, :metodo_pago, :telefono, 'pendiente')";
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

    public function listarTodos($filtro = null, $limit = null)
    {
        $sql = "SELECT * FROM pedidos";
        if ($filtro) {
            $sql .= " WHERE estado = :estado";
        }
        $sql .= " ORDER BY fecha DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->db->prepare($sql);
        if ($filtro) {
            $stmt->execute(['estado' => $filtro]);
        } else {
            $stmt->execute();
        }
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
    public function obtenerPorUsuario($userId, $limit = null)
    {
        // Verificar si la columna user_id existe
        try {
            $sql = "SELECT * FROM pedidos WHERE user_id = ? ORDER BY fecha DESC";
            if ($limit) {
                $sql .= " LIMIT " . intval($limit);
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
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
