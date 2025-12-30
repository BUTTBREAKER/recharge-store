<?php

namespace RECHARGE\Controllers;

use Flight;
use Leaf\Http\Session;
use RECHARGE\models\User;

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
        Flight::render('layout', ['title' => 'Iniciar Sesión - SisifoFamily']);
    }

    public static function registerView()
    {
        if (Session::has('user_id')) {
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
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role']);

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
            Session::set('user_role', $user['role']);
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
}
