<?php

declare(strict_types=1);

namespace Tests\Support;

use PHPUnit\Framework\TestCase;

/**
 * Base class for black-box HTTP tests.
 *
 * Spawns the real PHP built-in server in setUpBeforeClass() and tears it
 * down in tearDownAfterClass(), so every test talks to the app over HTTP
 * exactly like the Next.js frontend will.
 *
 * Every test starts from a clean slate: ensureSchema() (once per process),
 * then truncateAll() + seed() so the admin and test users always exist with
 * known passwords. The server is pointed at `recharge_test` (see
 * TestServer), never at the dev `recharge_db`.
 */
abstract class ApiTestCase extends TestCase
{
    protected ApiClient $api;

    public static function setUpBeforeClass(): void
    {
        TestServer::start();
    }

    public static function tearDownAfterClass(): void
    {
        TestServer::stop();
    }

    protected function setUp(): void
    {
        Database::ensureSchema();
        Database::truncateAll();
        Database::seed();

        $this->api = new ApiClient('http://127.0.0.1:' . TestServer::DEFAULT_PORT);
    }

    /** POST /api/login and return the Bearer token (asserts a 200). */
    protected function loginAs(string $email, string $password): string
    {
        $body = $this->loginResponse($email, $password);
        $this->assertArrayHasKey('token', $body);

        return (string) $body['token'];
    }

    /**
     * Full /api/login response body (token + user) for tests that also need
     * the authenticated user's id.
     *
     * @return array<string, mixed>
     */
    protected function loginResponse(string $email, string $password): array
    {
        $res = $this->api->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertSame(200, $res['status'], 'Login failed: ' . json_encode($res['body']));
        $this->assertIsArray($res['body']);

        return $res['body'];
    }
}
