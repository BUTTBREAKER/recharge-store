<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ApiAuth;
use App\Models\Pedido;
use App\Models\User;
use Flight;

/**
 * Perfil de usuario autenticado (Bearer). Los endpoints exigen identidad
 * funcional: sin uid no hay datos personales que devolver.
 */
final class ProfileController
{
    private const ALLOWED_AVATAR_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    private const MAX_AVATAR_SIZE = 2 * 1024 * 1024; // 2 MB

    /** GET /api/profile */
    public static function show(): void
    {
        $auth = ApiAuth::requireUser();

        $user = (new User())->obtenerPorId($auth['uid']);
        if (!$user) {
            Flight::jsonHalt(['message' => 'Usuario no encontrado.'], 404);
        }

        Flight::json(['data' => self::present($user)]);
    }

    /** PUT /api/profile — name y email obligatorios; avatar opcional (multipart). */
    public static function update(): void
    {
        $auth = ApiAuth::requireUser();
        $data = Flight::request()->data;

        $name = trim((string) ($data->name ?? ''));
        $email = trim((string) ($data->email ?? ''));

        if ($name === '' || $email === '') {
            Flight::json(['message' => 'Nombre y email son obligatorios.'], 422);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flight::json(['message' => 'El email no es válido.'], 422);
            return;
        }

        $userModel = new User();
        $takenId = $userModel->exists($email);
        if ($takenId !== false && (int) $takenId !== (int) $auth['uid']) {
            Flight::json(['message' => 'El email ya está registrado.'], 409);
            return;
        }

        $profile = ['name' => $name, 'email' => $email];

        $files = Flight::request()->files;
        if (isset($files['avatar']) && $files['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $profile['avatar_url'] = self::storeAvatar((int) $auth['uid'], $files['avatar']);
        }

        $userModel->actualizarPerfil($auth['uid'], $profile);

        Flight::json(['data' => self::present($userModel->obtenerPorId($auth['uid']))]);
    }

    /** PUT /api/profile/password */
    public static function updatePassword(): void
    {
        $auth = ApiAuth::requireUser();
        $data = Flight::request()->data;

        $current = (string) ($data->current_password ?? '');
        $new = (string) ($data->new_password ?? '');
        $confirm = (string) ($data->confirm_password ?? '');

        if ($current === '' || $new === '') {
            Flight::json(['message' => 'Contraseña actual y nueva son obligatorias.'], 422);
            return;
        }
        if (strlen($new) < 8) {
            Flight::json(['message' => 'La contraseña debe tener al menos 8 caracteres.'], 422);
            return;
        }
        if ($new !== $confirm) {
            Flight::json(['message' => 'Las contraseñas no coinciden.'], 422);
            return;
        }

        $userModel = new User();
        $user = $userModel->obtenerPorId($auth['uid']);
        if (!$user || !password_verify($current, $user['password'])) {
            Flight::json(['message' => 'La contraseña actual es incorrecta.'], 422);
            return;
        }

        $userModel->cambiarPassword($auth['uid'], $new);
        Flight::json(['message' => 'Contraseña actualizada.']);
    }

    /** GET /api/profile/orders?search=&estado= */
    public static function orders(): void
    {
        $auth = ApiAuth::requireUser();
        $query = Flight::request()->query;

        $pedidos = (new Pedido())->obtenerPorUsuario($auth['uid'], null, [
            'search' => $query->search ?? null,
            'estado' => $query->estado ?? null,
        ]);

        Flight::json(['data' => array_map([OrderController::class, 'present'], $pedidos)]);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * @param array{name: string, tmp_name: string, error: int, size: int} $file
     */
    private static function storeAvatar(int $userId, array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Flight::jsonHalt(['message' => 'Error al subir el avatar (código ' . $file['error'] . ').'], 422);
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_AVATAR_MIMES, true)) {
            Flight::jsonHalt(['message' => 'El avatar debe ser JPG, PNG o WEBP.'], 422);
        }
        if ($file['size'] > self::MAX_AVATAR_SIZE) {
            Flight::jsonHalt(['message' => 'El avatar supera el máximo de 2 MB.'], 422);
        }

        $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        if ($extension === '' || !in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            Flight::jsonHalt(['message' => 'Extensión de avatar no permitida.'], 422);
        }

        $directory = dirname(__DIR__, 4) . '/public/uploads/avatars';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            Flight::jsonHalt(['message' => 'No se pudo crear el directorio de avatares.'], 500);
        }

        $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
            Flight::jsonHalt(['message' => 'No se pudo guardar el avatar.'], 500);
        }

        // Ruta absoluta para que el frontend pueda cargarla tal cual.
        return '/uploads/avatars/' . $filename;
    }

    private static function present(array $user): array
    {
        $avatar = $user['avatar_url'] ?? null;
        // Registros viejos guardan 'uploads/...' (relativo): normalizamos.
        if (is_string($avatar) && $avatar !== '' && !str_starts_with($avatar, '/')) {
            $avatar = '/' . ltrim($avatar, '/');
        }

        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar_url' => $avatar,
            'created_at' => $user['created_at'] ?? null,
        ];
    }
}
