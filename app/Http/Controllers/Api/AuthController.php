<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ApiAuth;
use App\Models\User;
use Flight;

/**
 * Endpoints de autenticación de la API.
 * Devuelven un Bearer token firmado (stateless) que el frontend Next.js
 * envía en la cabecera Authorization.
 */
final class AuthController
{
    public static function login(): void
    {
        $data = Flight::request()->data;
        $email = (string) ($data->email ?? '');
        $password = (string) ($data->password ?? '');

        if ($email === '' || $password === '') {
            Flight::json(['message' => 'Email y contraseña son obligatorios.'], 422);
            return;
        }

        $user = (new User())->login($email, $password);
        if (!$user) {
            Flight::json(['message' => 'Credenciales incorrectas.'], 401);
            return;
        }

        Flight::json([
            'token' => ApiAuth::issueToken($user),
            'expires_in' => 604800,
            'user' => self::present($user),
        ]);
    }

    public static function register(): void
    {
        $data = Flight::request()->data;
        $name = trim((string) ($data->name ?? ''));
        $email = trim((string) ($data->email ?? ''));
        $password = (string) ($data->password ?? '');
        $confirm = (string) ($data->confirm_password ?? '');

        if ($name === '' || $email === '' || $password === '') {
            Flight::json(['message' => 'Nombre, email y contraseña son obligatorios.'], 422);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::json(['message' => 'El email no es válido.'], 422);
            return;
        }
        if (strlen($password) < 8) {
            Flight::json(['message' => 'La contraseña debe tener al menos 8 caracteres.'], 422);
            return;
        }
        if ($password !== $confirm) {
            Flight::json(['message' => 'Las contraseñas no coinciden.'], 422);
            return;
        }

        $userModel = new User();
        $created = $userModel->register([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if (!$created) {
            Flight::json(['message' => 'El email ya está registrado.'], 409);
            return;
        }

        $user = $userModel->login($email, $password);
        Flight::json([
            'token' => ApiAuth::issueToken($user),
            'expires_in' => 604800,
            'user' => self::present($user),
        ], 201);
    }

    public static function logout(): void
    {
        // Tokens stateless: el cliente (Next.js) descarta el token.
        // En modo estricto esta ruta exige Bearer válido (prefijo /api/logout).
        Flight::json(['message' => 'Sesión cerrada. Descarta el token en el cliente.']);
    }

    /** Expone solo los campos públicos del usuario (nunca el hash). */
    private static function present(array $user): array
    {
        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar_url' => $user['avatar_url'] ?? null,
            'created_at' => $user['created_at'] ?? null,
        ];
    }
}
