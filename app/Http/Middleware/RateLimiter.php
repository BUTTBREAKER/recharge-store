<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Leaf\Http\Session;
use Flight;

final readonly class RateLimiter
{
    /**
     * Limita intentos por IP/Sesión
     * Por ahora usaremos sesión por simplicidad en XAMPP
     */
    public static function loginLimit(): void
    {
        $request = Flight::request();

        if ($request->method === 'POST' && ($request->url === '/login' || $request->url === '/admin/login')) {
            $attempts = Session::get('login_attempts') ?? 0;
            $lastAttempt = Session::get('last_login_attempt') ?? 0;

            // Si ha pasado más de 15 minutos, resetear intentos
            if (time() - $lastAttempt > 900) {
                $attempts = 0;
                Session::set('login_attempts', 0);
            }

            if ($attempts >= 5) {
                $waitTime = 900 - (time() - $lastAttempt);
                $minutes = ceil($waitTime / 60);

                Flight::halt(429, "Demasiados intentos de inicio de sesión. Por favor, intenta de nuevo en $minutes minutos.");
            }
        }
    }

    public static function registerAttempt(): void
    {
        Session::set(
            'login_attempts',
            (Session::get('login_attempts') ?? 0) + 1,
        );

        Session::set('last_login_attempt', time());
    }

    public static function resetAttempts(): void
    {
        Session::set('login_attempts', 0);
        Session::set('last_login_attempt', null);
    }
}
