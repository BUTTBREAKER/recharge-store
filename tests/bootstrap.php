<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Same env loading as public/index.php: .env.example first, .env overrides it.
// ROOT_FOLDER equivalent: __DIR__ . '/..'
(new Dotenv())->load(__DIR__ . '/../.env.example', __DIR__ . '/../.env');
