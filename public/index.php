<?php

declare(strict_types=1);

use flight\Container;
use Leaf\Auth;
use Leaf\Db;
use Leaf\Helpers\Password;
use App\Enums\Role;
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
Container::getInstance()->singleton(PDO::class, static fn(): PDO => new PDO(
    "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']};charset={$_ENV['DB_CHARSET']}",
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
));

Container::getInstance()->singleton(Auth::class);
Container::getInstance()->singleton(Db::class);
Container::getInstance()->singleton(SluggerInterface::class, AsciiSlugger::class);

//////////////////////////////
// FLIGHTPHP CONFIGURATIONS //
//////////////////////////////
Flight::registerContainerHandler(Container::getInstance());

Flight::set(
    'flight.base_url',
    str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])
);

Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', false);
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->extension = '.php';
Flight::view()->path = __DIR__ . '/../resources/views';
Flight::view()->preserveVars = false;

///////////////////////////////
// LEAFS/AUTH CONFIGURATIONS //
///////////////////////////////
$auth = Container::getInstance()->get(Auth::class);
$db = Container::getInstance()->get(Db::class);

$auth->config('id.key', 'id');
$auth->config('db.table', 'usuarios');
$auth->config('roles.key', 'roles');
$auth->config('timestamps', false);
$auth->config('timestamps.format', 'YYYY-MM-DD HH:mm:ss');

$auth->config(
    'password.encode',
    static fn(string $password): string => Password::hash(
        $password,
        Password::BCRYPT,
        ['cost' => $_ENV['BCRYPT_ROUNDS']],
    )
);

$auth->config('password.verify', Password::verify(...));
$auth->config('password.key', 'clave_encriptada');
$auth->config('unique', ['email', 'cedula']);
$auth->config('hidden', []);
$auth->config('session', true);
$auth->config('session.lifetime', 0);

$auth->config('session.cookie', [
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);

$auth->config('token.lifetime', null);
$auth->config('token.secret', $_ENV['TOKEN_SECRET']);

$auth->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
$auth->config('messages.loginPasswordError', $auth->config('messages.loginParamsError'));

$auth->createRoles([
    Role::ADMIN->name => [
        // ...
    ],
]);

// DISABLE SSL
// $httpClient = $auth->client('google')->getHttpClient();
// $httpClientConfigProperty = new ReflectionProperty($httpClient, 'config');
// $httpClientConfigValue = $httpClientConfigProperty->getValue($httpClient);
// $httpClientConfigValue['verify'] = false;
// $httpClientConfigProperty->setValue($httpClient, $httpClientConfigValue);

// DATABASE INSTANCE AS SINGLETON
$db->connection(Container::getInstance()->get(PDO::class));
(new ReflectionProperty($auth, 'db'))->setValue($auth, $db);

// HELPERS
require_once ROOT_FOLDER_PATH . '/app/Helpers/csrf.php';

// MIDDLEWARES
Flight::before('start', [App\Http\Middleware\RateLimiter::class, 'loginLimit']);
Flight::before('start', [App\Http\Middleware\VerifyCsrfToken::class, 'handle']);
// API: CORS global para que las respuestas de error (404/401/403) también
// salgan con los headers CORS y el navegador pueda leerlas. La autenticación
// es middleware por grupo de rutas (ver routes/api.php).
Flight::before('start', [App\Http\Middleware\ApiCors::class, 'handle']);

// LOAD ROUTES
foreach (glob(__DIR__ . '/../routes/*.php') ?: [] as $routes) {
    require_once $routes;
}

Flight::start();
