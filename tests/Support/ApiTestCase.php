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
 * Phase 2 note: setUp() will truncate the recharge_test tables (keeping the
 * seeded admin) before each test. Not implemented yet — phases 0–1 only
 * cover the harness and unit tests.
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
        $this->api = new ApiClient('http://127.0.0.1:' . TestServer::DEFAULT_PORT);
    }
}
