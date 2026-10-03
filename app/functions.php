<?php

declare(strict_types=1);

use Leaf\Http\Session;

function _env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

/** Obtiene el token CSRF actual de la sesión */
function csrf_token(): string
{
    if (!Session::has('_csrf_token')) {
        Session::set('_csrf_token', bin2hex(random_bytes(32)));
    }

    return Session::get('_csrf_token');
}

/** Genera un campo HTML hidden con el token CSRF */
function csrf_field(): void
{
    $token = csrf_token();

    echo "<input type='hidden' name='_csrf_token' value='{$token}'>";
}
