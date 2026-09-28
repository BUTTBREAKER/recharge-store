<?php

declare(strict_types=1);

namespace Tests\Support;

use RuntimeException;

/**
 * Spawns and tears down the black-box HTTP test server.
 *
 * Uses the PHP built-in server against the real public/ front controller so
 * tests exercise the genuine bootstrap (index.php hooks, CORS, CSRF,
 * middleware order) without touching application code.
 */
final class TestServer
{
    /** Default port; the dev server uses 61001, so tests never collide with it. */
    public const DEFAULT_PORT = 61099;

    /** @var resource|null */
    private static $process = null;

    /** @var array<int, resource> pipes created by proc_open */
    private static array $pipes = [];

    private static int $port = self::DEFAULT_PORT;

    public static function start(int $port = self::DEFAULT_PORT): void
    {
        if (self::$process !== null) {
            return; // already running (idempotent)
        }

        self::$port = $port;

        $root = dirname(__DIR__, 2);
        // variables_order=EGPCS: the built-in server SAPI starts with an empty
        // $_ENV under the default "GPCS", and index.php's PDO reads
        // $_ENV['DB_NAME'] with no getenv() fallback. Without the E, Symfony
        // Dotenv would populate DB_NAME from .env (recharge_db) and the tests
        // would hit the dev database. With E, the environment we pass below
        // (DB_NAME=recharge_test) is visible in $_ENV first, and Dotenv never
        // overrides an already-set variable.
        $command = ['php', '-d', 'variables_order=EGPCS', '-S', "127.0.0.1:{$port}", '-t', 'public'];

        // Inherited environment plus API_AUTH_STRICT=true (production-like).
        $env = getenv(); // full process environment (keeps PATH for execvp)
        foreach ($_ENV as $key => $value) {
            $env[$key] = (string) $value;
        }
        $env['API_AUTH_STRICT'] = 'true';
        // Tests run against recharge_test only; .env (recharge_db) must never
        // be reached. Two conditions make that stick inside index.php:
        //  (1) the -d variables_order=EGPCS above puts DB_NAME into the
        //      child's $_ENV (the built-in SAPI would otherwise start with an
        //      empty $_ENV and Dotenv would fill it from .env);
        //  (2) SYMFONY_DOTENV_VARS must NOT be inherited: the parent process
        //      sets it when bootstrap loads .env, and Dotenv uses that list to
        //      decide which vars it "owns" — inheriting it would make Dotenv
        //      override DB_NAME back to the .env value (recharge_db).
        unset($env['SYMFONY_DOTENV_VARS']);
        $env['DB_NAME'] = 'recharge_test';

        $process = proc_open(
            $command,
            [
                0 => ['file', '/dev/null', 'r'],
                1 => ['file', '/dev/null', 'w'],
                2 => ['file', '/dev/null', 'w'],
            ],
            $pipes,
            $root,
            $env,
        );

        if (!is_resource($process)) {
            throw new RuntimeException("Failed to start test server on port {$port}.");
        }

        self::$process = $process;
        self::$pipes = $pipes;

        self::waitUntilReady($port);
    }

    public static function stop(): void
    {
        if (self::$process === null) {
            return; // idempotent
        }

        foreach (self::$pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }
        self::$pipes = [];

        proc_terminate(self::$process);
        proc_close(self::$process);
        self::$process = null;
    }

    private static function waitUntilReady(int $port): void
    {
        $url = "http://127.0.0.1:{$port}/api/health";
        $context = stream_context_create([
            'http' => ['method' => 'GET', 'timeout' => 1, 'ignore_errors' => true],
        ]);

        for ($attempt = 0; $attempt < 50; $attempt++) {
            $response = @file_get_contents($url, false, $context);
            if ($response !== false) {
                return;
            }
            usleep(100_000); // 100ms between probes
        }

        self::stop();
        throw new RuntimeException("Test server did not become ready at {$url}.");
    }
}
