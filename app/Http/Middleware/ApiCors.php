<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Flight;

/**
 * CORS para las rutas /api — permite que el frontend Next.js
 * (otro origen/puerto) consuma la API.
 *
 * Origen permitido: variable de entorno CORS_ORIGIN
 * (default http://localhost:3000, el dev server de Next).
 * El preflight OPTIONS se responde aquí mismo con 204.
 */
final readonly class ApiCors
{
    public static function handle(): void
    {
        $url = Flight::request()->url;

        if (!str_starts_with($url, '/api')) {
            return;
        }

        $origin = $_ENV['CORS_ORIGIN'] ?? 'http://localhost:3000';

        Flight::response()
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header(
                'Access-Control-Allow-Headers',
                'Authorization, Content-Type, X-Requested-With',
            )
            ->header(
                'Access-Control-Allow-Methods',
                'GET, POST, PUT, DELETE, OPTIONS',
            );

        if (Flight::request()->method === 'OPTIONS') {
            Flight::halt(204, '');
        }
    }
}
