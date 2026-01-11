<?php

namespace App\Http\Middleware;

use Leaf\Http\Session;
use Flight;

class VerifyCsrfToken
{
    /**
     * Rutas excluidas de validación CSRF (ej: webhooks de pago)
     */
    protected static $except = [
        '/api/binance/webhook',
        '/pago/binance/callback',
        '/ajax/settings/theme'
    ];

    public static function handle()
    {
        $request = Flight::request();
        
        // Solo validar en métodos que modifican estado (POST, PUT, DELETE, PATCH)
        if (in_array($request->method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            
            // Verificar excepciones por URL (exacta o parcial)
            foreach (self::$except as $exceptPath) {
                if (strpos($request->url, $exceptPath) !== false) {
                    return;
                }
            }

            $token = $request->data->_csrf_token ?? $request->query->_csrf_token ?? $request->getVar('HTTP_X_CSRF_TOKEN');

            if (!$token || $token !== Session::get('_csrf_token')) {
                $isAjax = $request->getVar('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest' || strpos($request->url, '/ajax/') !== false;

                if ($isAjax) {
                    Flight::halt(403, json_encode([
                        'error' => 'CSRF token mismatch',
                        'message' => 'Tu sesión ha expirado. Por favor, recarga la página.'
                    ]));
                } else {
                    Flight::halt(403, "<h1>403 Forbidden</h1><p>CSRF token mismatch. Tu sesión ha expirado o la solicitud es inválida.</p><p><a href='" . ($request->referrer ?? '/') . "'>Volver</a></p>");
                }
            }
        }
    }

    /**
     * Generar y obtener el token CSRF para la sesión actual
     */
    public static function generateToken()
    {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('_csrf_token');
    }
}
