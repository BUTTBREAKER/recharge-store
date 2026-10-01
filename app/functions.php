<?php

declare(strict_types=1);

use App\Http\Middleware\VerifyCsrfToken;

function _env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

/** Obtiene el token CSRF actual de la sesión */
function csrf_token(): string
{
    return VerifyCsrfToken::generateToken();
}

/** Genera un campo HTML hidden con el token CSRF */
function csrf_field(): void
{
    $token = csrf_token();

    echo "<input type='hidden' name='_csrf_token' value='{$token}'>";
}
