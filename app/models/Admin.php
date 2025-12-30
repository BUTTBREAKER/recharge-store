<?php

namespace App\models;

class Admin extends BaseModel
{
    public function login($usuario, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
}
