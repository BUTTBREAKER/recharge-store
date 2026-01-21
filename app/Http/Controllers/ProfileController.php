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
        Flight::render('layout', ['title' => 'Mi Perfil - FearSold']);
    }

    /**
     * Actualizar perfil de usuario
     */
    public static function updateProfile()
    {
        self::checkAuth();
        $userId = Session::get('user_id');

        $data = [
            'name' => Flight::request()->data->name,
            'email' => Flight::request()->data->email
        ];

        // Manejar subida de Avatar
        $files = Flight::request()->files;
        if (isset($files['avatar']) && $files['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array(mime_content_type($files['avatar']['tmp_name']), $allowedTypes)) {
                $ext = pathinfo($files['avatar']['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
                $uploadPath = 'uploads/avatars/' . $filename;
                
                // Asegurar que el directorio existe
                $fullPath = __DIR__ . '/../../../public/uploads/avatars/';
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                }

                if (move_uploaded_file($files['avatar']['tmp_name'], $fullPath . $filename)) {
                    $data['avatar_url'] = $uploadPath;
                    
                    // Actualizar sesión si es necesario
                    Session::set('user_avatar', $uploadPath);
                }
            }
        }

        $userModel = new User();
        $userModel->actualizarPerfil($userId, $data);

        Session::set('user_name', $data['name']);
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

        $search = Flight::request()->query->search ?? null;
        $estado = Flight::request()->query->estado ?? null;

        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorUsuario(Session::get('user_id'), null, [
            'search' => $search,
            'estado' => $estado
        ]);

        Flight::render('user/order-history', [
            'pedidos' => $pedidos,
            'search' => $search,
            'estado' => $estado
        ], 'content');
        Flight::render('layout', ['title' => 'Historial de Pedidos - FearSold']);
    }
}
