<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv)->load(__DIR__ . '/../.env.example', __DIR__ . '/../.env');

try {
    $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hash for 'password123'
    $hash = '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa';

    // Check if user exists first to decide between INSERT or UPDATE if ON DUPLICATE doesn't work as expected (though it should)
    // Actually, let's just use the query from before.
    $sql = "
        INSERT INTO users (name, email, password, role) VALUES ('Administrador', 'admin@sisifo.store', '$hash', 'admin')
        ON DUPLICATE KEY UPDATE password = '$hash', role = 'admin'
    ";

    $pdo->exec($sql);
    echo "Admin password reset successfully.\n";
} catch (PDOException $e) {
    echo "Error: {$e->getMessage()}\n";
}
