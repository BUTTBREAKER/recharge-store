<?php

namespace App\Http\Controllers;

use Flight;
use Leaf\Http\Session;
use App\Models\User;

class AuthController
{
    public static function loginView()
    {
        if (Session::has('user_id')) {
            if (Session::get('user_role') === 'admin') {
                Flight::redirect('/admin/dashboard');
            } else {
                Flight::redirect('/');
            }
        }

        Flight::render('auth/login', [], 'content');
        Flight::render('layout', ['title' => 'Iniciar Sesión - FearSold']);
    }

    public static function registerView()
    {
        if (Session::has('user_id')) {
            Flight::redirect('/');
        }

        Flight::render('auth/register', [], 'content');
        Flight::render('layout', ['title' => 'Únete a FearSold']);
    }

    public static function login()
    {
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role']);
            if (!empty($user['avatar_url'])) {
                Session::set('user_avatar', $user['avatar_url']);
            }

            if ($user['role'] === 'admin') {
                Flight::redirect('/admin/dashboard');
            } else {
                Flight::redirect('/');
            }
        } else {
            Flight::redirect('/login?error=1');
        }
    }

    public static function register()
    {
        $data = Flight::request()->data;

        // Basic validation
        if ($data->password !== $data->confirm_password) {
            Flight::redirect('/register?error=passwords_mismatch');
            return;
        }

        $userModel = new User();
        if (
            $userModel->register([
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password
            ])
        ) {
            // Auto login
            $user = $userModel->login($data->email, $data->password);
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role']);
            // Avatar is null for new users
            Flight::redirect('/');
        } else {
            Flight::redirect('/register?error=email_exists');
        }
    }

    public static function logout()
    {
        Session::destroy();
        Flight::redirect('/login');
    }

    // === Recuperación de Contraseña ===

    public static function forgotPasswordView()
    {
        Flight::render('auth/forgot_password', [], 'content');
        Flight::render('layout', ['title' => 'Recuperar Contraseña - FearSold']);
    }

    public static function sendResetLink()
    {
        $email = Flight::request()->data->email;
        $userModel = new User();
        
        if ($userModel->exists($email)) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora

            // Guardar token en DB (Usando clase anónima o modelo simple por ahora)
            // Idealmente crear PasswordReset Model, pero por brevedad usaremos PDO directo del userModel
            $db = Flight::db(); // Asumiendo que Flight::db() devuelve la conexión PDO si está registrada, 
            // O podemos usar el UserModel para acceder a la DB
            
            // Vamos a usar una consulta directa rápida
            // Nota: Esto depende de cómo está configurado Flight::db(), en este proyecto parece que los modelos extienden BaseModel
            // Así que usaré una instancia de UserModel para acceder a $this->db
            
            // Corrección: usaremos un método en User o creamos un modelo rápido.
            // Para mantenerlo limpio, agregaremos un método a User "guardarTokenReset"
            $userModel->guardarTokenReset($email, $token, $expires);

            // SIMULACIÓN DE ENVÍO DE EMAIL
            // En producción aquí iría PHPmailer
            // Por ahora, redirigimos con un mensaje de "revisa tu correo"
            // Y para propósitos de DEMO, mostramos el link en un log o mensaje flash temporal (solo debug)
            
            // Guardamos el link en sesión para demo (ELIMINAR EN PRODUCCIÓN)
            Session::set('demo_reset_link', "/reset-password?token=$token");
        }

        Flight::redirect('/forgot-password?success=1');
    }

    public static function resetPasswordView()
    {
        $token = Flight::request()->query->token;
        if (!$token) {
            Flight::redirect('/login');
        }
        
        Flight::render('auth/reset_password', ['token' => $token], 'content');
        Flight::render('layout', ['title' => 'Restablecer Contraseña']);
    }

    public static function resetPassword()
    {
        $token = Flight::request()->data->token;
        $password = Flight::request()->data->password;
        $confirm = Flight::request()->data->confirm_password;

        if ($password !== $confirm) {
            Flight::redirect("/reset-password?token=$token&error=mismatch");
            return;
        }

        $userModel = new User();
        $email = $userModel->verificarTokenReset($token);

        if ($email) {
            // Obtener ID usuario por email
            // Requerimos método obtenerPorEmail
            $user = $userModel->obtenerPorEmail($email);
            if ($user) {
                $userModel->cambiarPassword($user['id'], $password);
                $userModel->borrarTokenReset($token);
                Flight::redirect('/login?success=password_reset');
                return;
            }
        }

        Flight::redirect("/reset-password?token=$token&error=invalid");
    }
}
