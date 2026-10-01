<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

const ROOT_FOLDER_PATH = __DIR__ . '/..';

require_once ROOT_FOLDER_PATH . '/vendor/autoload.php';

// Same env loading as public/index.php: .env.example first, .env overrides it.
new Dotenv()->load(
    ROOT_FOLDER_PATH . '/.env.example',
    ROOT_FOLDER_PATH . '/.env',
);
