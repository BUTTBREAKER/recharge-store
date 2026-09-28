<?php

namespace App\Models;

class Juego extends BaseModel
{
    protected $table = 'juegos';

    /**
     * Listar todos los juegos
     */
    public function listarTodos($soloActivos = true)
    {
        $sql = "SELECT * FROM juegos WHERE 1=1";
        
        if ($soloActivos) {
            $sql .= " AND activo = 1";
        }
        
        $sql .= " ORDER BY orden ASC, id ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener juego por ID
     */
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM juegos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtener juego por slug
     */
    public function obtenerPorSlug($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM juegos WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    /**
     * Crear nuevo juego
     */
    public function crear($datos)
    {
        $stmt = $this->db->prepare("
            INSERT INTO juegos (nombre, slug, descripcion, imagen, icono, orden, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $datos['nombre'],
            $datos['slug'],
            $datos['descripcion'] ?? null,
            $datos['imagen'] ?? null,
            $datos['icono'] ?? '🎮',
            $datos['orden'] ?? 0,
            $datos['activo'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualizar juego
     */
    public function actualizar($id, $datos)
    {
        $stmt = $this->db->prepare("
            UPDATE juegos 
            SET nombre = ?, slug = ?, descripcion = ?, imagen = ?, icono = ?, orden = ?, activo = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $datos['nombre'],
            $datos['slug'],
            $datos['descripcion'] ?? null,
            $datos['imagen'] ?? null,
            $datos['icono'] ?? '🎮',
            $datos['orden'] ?? 0,
            $datos['activo'] ?? 1,
            $id
        ]);
    }

    /**
     * Eliminar juego
     */
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM juegos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Toggle activo/inactivo
     */
    public function toggleActivo($id)
    {
        $stmt = $this->db->prepare("UPDATE juegos SET activo = NOT activo WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Generar slug único desde nombre
     */
    public static function generarSlug($nombre)
    {
        $slug = strtolower(trim($nombre));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
