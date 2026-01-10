<?php

namespace App\Http\Controllers;

use Flight;
use Leaf\Http\Session;
use App\Models\Pedido;
use App\Models\User;

class ProfileController
{
    public static function checkAuth()
    {
        if (!Session::has('user_id')) {
            Flight::redirect('/login');
            exit;
        }
    }

    /**
     * Perfil de usuario regular
     */
    public static function userProfile()
    {
        self::checkAuth();

        $userModel = new User();
        $user = $userModel->obtenerPorId(Session::get('user_id'));

        // Obtener últimos 5 pedidos del usuario
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorUsuario(Session::get('user_id'), 5);

        Flight::render('user_profile', [
            'user' => $user,
            'pedidos' => $pedidos
        ], 'content');
        Flight::render('layout', ['title' => 'Mi Perfil - SisifoStore']);
    }

    /**
     * Actualizar perfil de usuario
     */
    public static function updateProfile()
    {
        self::checkAuth();

        $userModel = new User();
        $userModel->actualizarPerfil(Session::get('user_id'), [
            'name' => Flight::request()->data->name,
            'email' => Flight::request()->data->email
        ]);

        Session::set('user_name', Flight::request()->data->name);
        Flight::redirect('/profile?success=1');
    }

    /**
     * Cambiar contraseña de usuario
     */
    public static function changePassword()
    {
        self::checkAuth();

        $currentPassword = Flight::request()->data->current_password;
        $newPassword = Flight::request()->data->new_password;
        $confirmPassword = Flight::request()->data->confirm_password;

        if ($newPassword !== $confirmPassword) {
            Flight::redirect('/profile?password_error=mismatch');
            return;
        }

        $userModel = new User();
        $user = $userModel->obtenerPorId(Session::get('user_id'));

        if (password_verify($currentPassword, $user['password'])) {
            $userModel->cambiarPassword(Session::get('user_id'), $newPassword);
            Flight::redirect('/profile?password_success=1');
        } else {
            Flight::redirect('/profile?password_error=incorrect');
        }
    }

    /**
     * Historial de pedidos del usuario
     */
    public static function orderHistory()
    {
        self::checkAuth();

        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorUsuario(Session::get('user_id'));

        Flight::render('user/order-history', [
            'pedidos' => $pedidos
        ], 'content');
        Flight::render('layout', ['title' => 'Historial de Pedidos - SisifoStore']);
    }
}
