<?php

namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected $table = 'users';

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function register($data)
    {
        // Verificar si el email ya existe
        if ($this->exists($data['email'])) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, email, password, role) VALUES (?, ?, ?, 'user')");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function exists($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil($id, $datos)
    {
        $fields = ["name = :name", "email = :email"];
        $params = ['name' => $datos['name'], 'email' => $datos['email'], 'id' => $id];

        if (isset($datos['avatar_url'])) {
            $fields[] = "avatar_url = :avatar_url";
            $params['avatar_url'] = $datos['avatar_url'];
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function cambiarPassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function obtenerPorEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === Password Reset Methods ===

    public function guardarTokenReset($email, $token, $expires)
    {
        // Borrar tokens anteriores
        $this->db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        
        $stmt = $this->db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $token, $expires]);
    }

    public function verificarTokenReset($token)
    {
        $stmt = $this->db->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetchColumn();
    }

    public function borrarTokenReset($token)
    {
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE token = ?");
        return $stmt->execute([$token]);
    }

    // === Métodos para Admin ===

    /**
     * Listar todos los usuarios
     */
    public function listarTodos($filtro = null)
    {
        $sql = "SELECT id, name, email, role, created_at FROM {$this->table}";
        $params = [];
        
        if ($filtro === 'admin') {
            $sql .= " WHERE role = 'admin'";
        } elseif ($filtro === 'user') {
            $sql .= " WHERE role = 'user'";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar usuarios por rol
     */
    public function contarPorRol($rol = null)
    {
        if ($rol) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE role = ?");
            $stmt->execute([$rol]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
            $stmt->execute();
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Actualizar usuario completo (admin)
     */
    public function actualizarCompleto($id, $datos)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([
            $datos['name'],
            $datos['email'],
            $datos['role'],
            $id
        ]);
    }

    /**
     * Cambiar rol de usuario
     */
    public function cambiarRol($id, $nuevoRol)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET role = ? WHERE id = ?");
        return $stmt->execute([$nuevoRol, $id]);
    }

    /**
     * Eliminar usuario
     */
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
