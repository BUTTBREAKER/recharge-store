<?php

namespace RECHARGE\controllers;

use Flight;
use RECHARGE\models\Pedido;
use RECHARGE\models\User;

class ProfileController {
    
    public static function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            exit;
        }
    }

    /**
     * Perfil de usuario regular
     */
    public static function userProfile() {
        self::checkAuth();
        
        $userModel = new User();
        $pedidoModel = new Pedido();
        
        $user = $userModel->obtenerPorId($_SESSION['user_id']);
        
        // Obtener pedidos del usuario (necesitaremos agregar esta funcionalidad)
        $pedidos = []; // Por ahora vacío, se puede implementar después
        
        Flight::render('user_profile', [
            'user' => $user,
            'pedidos' => $pedidos
        ], 'content');
        Flight::render('layout', ['title' => 'Mi Perfil - SisifoStore']);
    }

    /**
     * Actualizar perfil de usuario
     */
    public static function updateProfile() {
        self::checkAuth();
        
        $userModel = new User();
        $userModel->actualizarPerfil($_SESSION['user_id'], [
            'name' => Flight::request()->data->name,
            'email' => Flight::request()->data->email
        ]);
        
        $_SESSION['user_name'] = Flight::request()->data->name;
        
        Flight::redirect('/profile?success=1');
    }

    /**
     * Cambiar contraseña de usuario
     */
    public static function changePassword() {
        self::checkAuth();
        
        $currentPassword = Flight::request()->data->current_password;
        $newPassword = Flight::request()->data->new_password;
        $confirmPassword = Flight::request()->data->confirm_password;
        
        if ($newPassword !== $confirmPassword) {
            Flight::redirect('/profile?password_error=mismatch');
            return;
        }
        
        $userModel = new User();
        $user = $userModel->obtenerPorId($_SESSION['user_id']);
        
        if (password_verify($currentPassword, $user['password'])) {
            $userModel->cambiarPassword($_SESSION['user_id'], $newPassword);
            Flight::redirect('/profile?password_success=1');
        } else {
            Flight::redirect('/profile?password_error=incorrect');
        }
    }
}
