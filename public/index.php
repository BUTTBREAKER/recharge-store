<?php

use flight\Container;
use Leaf\Auth;
use Leaf\Db;
use Leaf\Helpers\Password;
use RECHARGE\Enums\Role;
use Symfony\Component\Dotenv\Dotenv;

///////////////
// CONSTANTS //
///////////////
const ROOT_FOLDER = __DIR__ . '/..';

require_once ROOT_FOLDER . '/vendor/autoload.php';

///////////////////////////
// ENVIRONMENT VARIABLES //
///////////////////////////
(new Dotenv())->load(ROOT_FOLDER . '/.env.example', ROOT_FOLDER . '/.env');

/////////////////////////////////////
// DEPENDENCIES INJECTOR CONTAINER //
/////////////////////////////////////
Container::getInstance()->singleton(PDO::class, static fn(): PDO => new PDO(
    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
));

Container::getInstance()->singleton(Auth::class);
Container::getInstance()->singleton(Db::class);

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
        ['cost' => 10],
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
// $guzzle = $auth->client('google')->getHttpClient();
// $refleccionPropiedad = new ReflectionProperty($guzzle, 'config');
// $refleccionPropiedad->setAccessible(true);
// $configuracionDeGuzzle = $refleccionPropiedad->getValue($guzzle);

// $refleccionPropiedad->setValue(
//     $guzzle,
//     ['verify' => false] + $configuracionDeGuzzle
// );

// DATABASE INSTANCE AS SINGLETON
$db->connection(Container::getInstance()->get(PDO::class));
(new ReflectionProperty($auth, 'db'))->setValue($auth, $db);

// LOAD ROUTES
foreach (glob(__DIR__ . '/../routes/*.php') as $routes) {
    require_once $routes;
}

Flight::start();
