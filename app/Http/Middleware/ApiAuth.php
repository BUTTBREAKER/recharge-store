<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Flight;
use SensitiveParameter;

/**
 * Autenticación Bearer para la API (patrón estilo Laravel).
 *
 * Emite tokens HMAC stateless (uid + role + exp firmados con TOKEN_SECRET)
 * y los valida desde la cabecera `Authorization: Bearer <token>`.
 *
 * Modo permisivo (default, API_AUTH_STRICT=false): pensado para pruebas —
 * solo adjunta el usuario si el token es válido, nunca rechaza la request.
 * Modo estricto (API_AUTH_STRICT=true): los endpoints protegidos responden
 * 401 sin token válido y 403 si el rol no es admin.
 */
final readonly class ApiAuth implements BeforeMiddleware
{
    /** TTL del token en segundos (7 días). */
    private const int TOKEN_TTL = 604_800;

    /**
     * @param bool $enforce    false = identidad opcional: adjunta el usuario
     *                         si hay token válido pero nunca rechaza
     *                         (checkout de invitado). true = exige token en
     *                         modo estricto.
     * @param bool $requireAdmin exige rol admin (solo aplica con $enforce).
     */
    public function __construct(
        private bool $enforce = true,
        private bool $requireAdmin = false,
    ) {
        // ...
    }

    public static function strict(): bool
    {
        $value = $_ENV['API_AUTH_STRICT'] ?? getenv('API_AUTH_STRICT');

        return filter_var(
            $value === false ? 'false' : $value,
            FILTER_VALIDATE_BOOLEAN,
        );
    }

    // ------------------------------------------------------------------
    // Emisión / validación de tokens
    // ------------------------------------------------------------------

    public static function issueToken(array $user): string
    {
        $payload = json_encode(
            [
                'uid' => (int) $user['id'],
                'role' => (string) ($user['role'] ?? 'user'),
                'exp' => time() + self::TOKEN_TTL,
            ],
            JSON_UNESCAPED_SLASHES,
        );

        return self::base64UrlEncode($payload) . '.' . self::sign($payload);
    }

    public static function verify(#[SensitiveParameter] string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$body, $signature] = $parts;
        $payload = self::base64UrlDecode($body);
        if (
            $payload === false
            || !hash_equals(self::sign($payload), $signature)
        ) {
            return null;
        }

        $data = json_decode($payload, true);

        if (!is_array($data) || !isset($data['uid'], $data['exp'])) {
            return null;
        }

        if ((int) $data['exp'] < time()) {
            return null;
        }

        return $data;
    }

    private static function sign(string $data): string
    {
        return hash_hmac(
            'sha256',
            $data,
            $_ENV['TOKEN_SECRET'] ?? '',
        );
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string|false
    {
        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    public static function bearerToken(): ?string
    {
        $header = Flight::request()->getVar('HTTP_AUTHORIZATION', '');

        if ($header === '' || stripos($header, 'bearer ') !== 0) {
            return null;
        }

        return trim(substr($header, 7));
    }

    // ------------------------------------------------------------------
    // Middleware de grupo (patrón Flight: Flight::group(..., [new ApiAuth()]))
    // ------------------------------------------------------------------

    /**
     * Se ejecuta antes de las rutas del grupo. Adjunta el usuario a la
     * request (en cualquier modo) y, solo en modo estricto, exige el token
     * y —si el grupo lo pidió— el rol admin.
     */
    public function before(array $params = []): bool
    {
        $token = self::bearerToken();

        $user = $token !== null ? self::verify($token) : null;

        if ($user !== null) {
            Flight::set('api.user', $user);
        }

        if (!$this->enforce || !self::strict()) {
            // Identidad opcional o modo pruebas: no se rechaza la request.
            return true;
        }

        if ($user === null) {
            Flight::jsonHalt(
                [
                    'message' => 'No autenticado.',
                    'hint' => 'Envía la cabecera Authorization: Bearer <token>.',
                ],
                401,
            );
        }

        if ($this->requireAdmin && ($user['role'] ?? '') !== 'admin') {
            Flight::jsonHalt(['message' => 'Requiere rol admin.'], 403);
        }

        return true;
    }

    // ------------------------------------------------------------------
    // Helpers para controladores
    // ------------------------------------------------------------------

    public static function user(): ?array
    {
        return Flight::get('api.user');
    }

    /**
     * Exige usuario autenticado en cualquier modo: sin uid no hay datos
     * personales que devolver (no es una regla de seguridad, es necesidad
     * funcional).
     */
    public static function requireUser(): array
    {
        $user = self::user();

        if ($user === null) {
            Flight::jsonHalt(
                ['message' => 'No autenticado.'],
                401,
            );
        }

        return $user;
    }

    /**
     * Exige rol admin. En modo permisivo se omite la regla para facilitar
     * las pruebas del panel sin andar rotando tokens.
     */
    public static function requireAdmin(): array
    {
        if (!self::strict()) {
            return self::user() ?? [];
        }

        $user = self::user();

        if ($user === null) {
            Flight::jsonHalt(['message' => 'No autenticado.'], 401);
        }

        if (($user['role'] ?? '') !== 'admin') {
            Flight::jsonHalt(['message' => 'Requiere rol admin.'], 403);
        }

        return $user;
    }
}
