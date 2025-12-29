<?php

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv)->load(__DIR__ . '/../.env.example', __DIR__ . '/../.env');

return [
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'name' => $_ENV['DB_NAME'] ?? 'recharge_db',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
    ],
    'binance' => [
        'api_key' => $_ENV['BINANCE_API_KEY'] ?? '',
        'secret_key' => $_ENV['BINANCE_SECRET_KEY'] ?? '',
        'callback_url' => $_ENV['BINANCE_CALLBACK_URL'] ?? '',
    ]
];
