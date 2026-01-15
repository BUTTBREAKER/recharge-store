<?php

use App\Http\Middleware\VerifyCsrfToken;

if (!function_exists('csrf_token')) {
    /**
     * Obtiene el token CSRF actual de la sesión
     */
    function csrf_token()
    {
        return VerifyCsrfToken::generateToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Genera un campo HTML hidden con el token CSRF
     */
    function csrf_field()
    {
        $token = csrf_token();
        echo '<input type="hidden" name="_csrf_token" value="' . $token . '">';
    }
}
