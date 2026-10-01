<?php

declare(strict_types=1);

use App\Http\Middleware\VerifyCsrfToken;

/** Obtiene el token CSRF actual de la sesión */
function csrf_token()
{
    return VerifyCsrfToken::generateToken();
}

/** Genera un campo HTML hidden con el token CSRF */
function csrf_field()
{
    $token = csrf_token();

    echo "<input type='hidden' name='_csrf_token' value='$token'>";
}
