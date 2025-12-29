<?php
class Producto {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    public function listarTodos($juego = null, $soloActivos = true) {
        $sql = "SELECT * FROM productos WHERE 1=1";
        $params = [];

        if ($juego) {
            $sql .= " AND juego = ?";
            $params[] = $juego;
        }

        if ($soloActivos) {
            $sql .= " AND activo = 1";
        }

        $sql .= " ORDER BY orden ASC, id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizarPrecio($id, $precio) {
        $stmt = $this->db->prepare("UPDATE productos SET precio = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$precio, $id]);
    }

    public function toggleActivo($id) {
        $stmt = $this->db->prepare("UPDATE productos SET activo = NOT activo WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerPorJuego($juego) {
        return $this->listarTodos($juego, true);
    }

    public function crear($datos) {
        $stmt = $this->db->prepare("
            INSERT INTO productos (juego, nombre, cantidad, precio, precio_original, orden, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $datos['juego'],
            $datos['nombre'],
            $datos['cantidad'],
            $datos['precio'],
            $datos['precio_original'] ?? null,
            $datos['orden'] ?? 0,
            $datos['activo'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    public function actualizar($id, $datos) {
        $stmt = $this->db->prepare("
            UPDATE productos 
            SET juego = ?, nombre = ?, cantidad = ?, precio = ?, precio_original = ?, orden = ?, activo = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $datos['juego'],
            $datos['nombre'],
            $datos['cantidad'],
            $datos['precio'],
            $datos['precio_original'] ?? null,
            $datos['orden'] ?? 0,
            $datos['activo'] ?? 1,
            $id
        ]);
    }
}
