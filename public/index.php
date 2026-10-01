<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Http\Middleware\ApiCors;
use App\Http\Middleware\RateLimiter;
use App\Http\Middleware\VerifyCsrfToken;
use flight\Container;
use Leaf\Auth;
use Leaf\Db;
use Leaf\Helpers\Password;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

///////////////
// CONSTANTS //
///////////////
const ROOT_FOLDER_PATH = __DIR__ . '/..';

require_once ROOT_FOLDER_PATH . '/vendor/autoload.php';

///////////////////////////
// ENVIRONMENT VARIABLES //
///////////////////////////
new Dotenv()->load(
    ROOT_FOLDER_PATH . '/.env.example',
    ROOT_FOLDER_PATH . '/.env',
);

// Los montos/precios de la API son floats: sin esto, entornos con
// serialize_precision alto emiten JSON con expansión binaria
// (p. ej. 1.51000000000000000888...) en vez de 1.51.
ini_set('serialize_precision', $_ENV['SERIALIZE_PRECISION']);

/////////////////////////////////////
// DEPENDENCIES INJECTOR CONTAINER //
/////////////////////////////////////
$container = Container::getInstance();

$container->singleton(Auth::class, static function (): Auth {
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
});

$dbFactory = static fn(): Db => $container->get(Auth::class)->db();

$pdoFactory = static function () use ($container): PDO {
    $pdo = $container->get(Db::class)->connection();

    assert($pdo instanceof PDO, description: 'Expected instance of PDO');

    return $pdo;
};

$googleFactory = static function () use ($container): Google {
    $httpClient = $container
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

$container->singleton(Db::class, $dbFactory);
$container->singleton(PDO::class, $pdoFactory);
$container->singleton(Google::class, $googleFactory);
$container->singleton(SluggerInterface::class, AsciiSlugger::class);

//////////////////////////////
// FLIGHTPHP CONFIGURATIONS //
//////////////////////////////
Flight::registerContainerHandler($container);

Flight::set('flight.base_url', str_replace(
    '/index.php',
    replace: '',
    subject: $_SERVER['SCRIPT_NAME'],
));

Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', false);
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->extension = '.php';
Flight::view()->path = ROOT_FOLDER_PATH . '/resources/views';
Flight::view()->preserveVars = false;

// MIDDLEWARES
Flight::before('start', RateLimiter::loginLimit(...));
Flight::before('start', VerifyCsrfToken::handle(...));

// API: CORS global para que las respuestas de error (404/401/403) también
// salgan con los headers CORS y el navegador pueda leerlas. La autenticación
// es middleware por grupo de rutas (ver routes/api.php).
Flight::before('start', ApiCors::handle(...));

// LOAD ROUTES
$routesPaths = glob(ROOT_FOLDER_PATH . '/routes/*.php');

foreach ($routesPaths ? $routesPaths : [] as $routes) {
    require_once $routes;
}

Flight::start();
