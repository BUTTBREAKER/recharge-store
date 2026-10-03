<?php

declare(strict_types=1);

use App\Enums\Role;
use flight\Container;
use Leaf\Auth;
use Leaf\Helpers\Password;
use Leaf\Http\Session;
use League\OAuth2\Client\Provider\Google;

$authFactory = static function (): Auth {
    $auth = new Auth();
    $auth->autoConnect();

    $auth->config('id.key', 'id');
    $auth->config('db.table', 'users');
    $auth->config('roles.key', 'roles');
    $auth->config('timestamps', false);
    $auth->config('timestamps.format', 'YYYY-MM-DD HH:mm:ss');

    $auth->config('password.encode', static fn(
        #[SensitiveParameter]
        string $password,
    ): string => Password::hash($password, Password::BCRYPT, [
        'cost' => $_ENV['BCRYPT_ROUNDS'],
    ]));

    $auth->config('password.verify', Password::verify(...));
    $auth->config('password.key', 'password');
    $auth->config('unique', ['email']);
    $auth->config('hidden', ['field.id', 'field.password']);
    $auth->config('session', true);
    $auth->config('session.lifetime', 0);

    $auth->config('session.cookie', [
        'secure' => false,
        'httponly' => true,
        'samesite' => 'strict',
    ]);

    $auth->config('token.lifetime', null);
    $auth->config('token.secret', $_ENV['TOKEN_SECRET']);

    $auth->config(
        'messages.loginParamsError',
        'Cédula o contraseña incorrecta',
    );

    $auth->config(
        'messages.loginPasswordError',
        $auth->config('messages.loginParamsError'),
    );

    $auth->createRoles([
        Role::ADMIN->name => [
            // ...
        ],
    ]);

    return $auth;
};

$googleFactory = static function (): Google {
    $httpClient = Container::getInstance()
        ->get(Auth::class)
        ->client('google')
        ?->getHttpClient();

    assert(
        $httpClient instanceof Google,
        description: 'Expected instance of Google',
    );

    $httpClientConfigProperty = new ReflectionProperty($httpClient, 'config');
    $httpClientConfigValue = $httpClientConfigProperty->getValue($httpClient);

    assert(
        is_array($httpClientConfigValue),
        description: 'Expected array config value',
    );

    $httpClientConfigValue['verify'] = false;
    $httpClientConfigProperty->setValue($httpClient, $httpClientConfigValue);

    return $httpClient;
};

Container::getInstance()->singleton(Auth::class, $authFactory);
Container::getInstance()->singleton(Google::class, $googleFactory);

Flight::before('start', static function (): void {
    $request = Flight::request();

    if (
        $request->method === 'POST'
        && ($request->url === '/login' || $request->url === '/admin/login')
    ) {
        $attempts = Session::get('login_attempts') ?? 0;
        $lastAttempt = Session::get('last_login_attempt') ?? 0;

        // Si ha pasado más de 15 minutos, resetear intentos
        if ((time() - $lastAttempt) > 900) {
            $attempts = 0;
            Session::set('login_attempts', 0);
        }

        if ($attempts >= 5) {
            $waitTime = 900 - (time() - $lastAttempt);
            $minutes = ceil($waitTime / 60);

            Flight::halt(
                429,
                "Demasiados intentos de inicio de sesión. Por favor, intenta de nuevo en {$minutes} minutos.",
            );
        }
    }
});

Flight::before('start', static function (): void {
    static $except = [
        '/api', // La API usa Bearer token, no cookies (ver ApiAuth)
        '/api/binance/webhook',
        '/pago/binance/callback',
        '/ajax/settings/theme',
    ];

    static $methodsToCheck = ['POST', 'PUT', 'DELETE', 'PATCH'];

    $request = Flight::request();

    // Solo validar en métodos que modifican estado (POST, PUT, DELETE, PATCH)
    if (in_array($request->method, $methodsToCheck, strict: true)) {
        // Verificar excepciones por URL (exacta o parcial)
        foreach ($except as $exceptPath) {
            if (str_contains($request->url, $exceptPath)) {
                return;
            }
        }

        $token = $request->data->_csrf_token
            ?? $request->query->_csrf_token
            ?? $request->getVar('HTTP_X_CSRF_TOKEN');

        if (!$token || $token !== Session::get('_csrf_token')) {
            $isAjax = (
                $request->getVar('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest'
                || str_contains($request->url, '/ajax/')
            );

            if ($isAjax) {
                Flight::halt(
                    403,
                    json_encode([
                        'error' => 'CSRF token mismatch',
                        'message' => 'Tu sesión ha expirado. Por favor, recarga la página.'
                    ]),
                );

                return;
            }

            Flight::halt(
                403,
                "<h1>403 Forbidden</h1><p>CSRF token mismatch. Tu sesión ha expirado o la solicitud es inválida.</p><p><a href='" . ($request->referrer ?? '/') . "'>Volver</a></p>",
            );
        }
    }
});
