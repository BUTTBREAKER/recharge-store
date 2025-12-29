<?php

namespace RECHARGE\controllers;

use Flight;
use RECHARGE\models\User;

class AuthController
{
    public static function loginView()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] === 'admin') {
                Flight::redirect('/admin/dashboard');
            } else {
                Flight::redirect('/');
            }
        }

        Flight::render('auth/login', [], 'content');
        Flight::render('layout', ['title' => 'Iniciar Sesión - SisifoFamily']);
    }

    public static function registerView()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            Flight::redirect('/');
        }

        Flight::render('auth/register', [], 'content');
        Flight::render('layout', ['title' => 'Unete a SisifoFamily']);
    }

    public static function login()
    {
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            Flight::redirect('/');
        } else {
            Flight::redirect('/register?error=email_exists');
        }
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        Flight::redirect('/login');
    }
}
