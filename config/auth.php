<?php

declare(strict_types=1);

use App\Enums\Role;
use flight\Container;
use Leaf\Auth;
use Leaf\Helpers\Password;
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
