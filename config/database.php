<?php

declare(strict_types=1);

use flight\Container;
use Leaf\Auth;
use Leaf\Db;

$container = Container::getInstance();

$dbFactory = static fn(): Db => $container->get(Auth::class)->db();

$pdoFactory = static function () use ($container): PDO {
    $pdo = $container->get(Db::class)->connection();

    assert($pdo instanceof PDO, description: 'Expected instance of PDO');

    return $pdo;
};

$container->singleton(Db::class, $dbFactory);
$container->singleton(PDO::class, $pdoFactory);
