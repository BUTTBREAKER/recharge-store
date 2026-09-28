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
 *
 * Uploaded-file cleanup: endpoints such as POST /api/payments/pagomovil
 * store real files under public/uploads/ (the served path comes back in the
 * response, e.g. `data.comprobante` = `/uploads/comp_..._....png`). setUp()
 * snapshots the directory listing and tearDown() deletes every entry that
 * was not there before the test, so no run leaves artifacts behind.
 */
abstract class ApiTestCase extends TestCase
{
    protected ApiClient $api;

    /**
     * Entries of public/uploads present before the current test started.
     * null = snapshot never taken → tearDown must NOT clean up (an empty
     * snapshot would delete pre-existing entries such as .gitkeep).
     */
    private ?array $uploadsSnapshot = null;

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
        // FIRST line: if anything below throws, tearDown still has a valid
        // baseline and can never wipe files that existed before the test.
        $this->uploadsSnapshot = self::uploadEntries();

        Database::ensureSchema();
        Database::truncateAll();
        Database::seed();

        $this->api = new ApiClient('http://127.0.0.1:' . TestServer::DEFAULT_PORT);
    }

    protected function tearDown(): void
    {
        if ($this->uploadsSnapshot !== null) {
            self::removeNewUploads($this->uploadsSnapshot);
        }
        parent::tearDown();
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

    // ------------------------------------------------------------------
    // Uploaded-file cleanup (public/uploads)
    // ------------------------------------------------------------------

    /** Absolute path of the runtime uploads directory (never committed data). */
    protected static function uploadsDir(): string
    {
        return dirname(__DIR__, 2) . '/public/uploads';
    }

    /** @return list<string> sorted entries of the uploads directory */
    private static function uploadEntries(): array
    {
        $dir = self::uploadsDir();
        if (!is_dir($dir)) {
            return [];
        }

        $entries = array_values(array_diff(scandir($dir) ?: [], ['.', '..']));
        sort($entries);

        return $entries;
    }

    /**
     * Delete every public/uploads entry created during the test (files and
     * directories such as uploads/avatars/), keeping the pre-test snapshot.
     *
     * @param list<string> $snapshot entries present before the test
     */
    private static function removeNewUploads(array $snapshot): void
    {
        $dir = self::uploadsDir();
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..' || in_array($entry, $snapshot, true)) {
                continue;
            }
            self::removePath($dir . '/' . $entry);
        }
    }

    private static function removePath(string $path): void
    {
        if (is_dir($path) && !is_link($path)) {
            foreach (scandir($path) ?: [] as $entry) {
                if ($entry !== '.' && $entry !== '..') {
                    self::removePath($path . '/' . $entry);
                }
            }
            rmdir($path);

            return;
        }

        if (file_exists($path) || is_link($path)) {
            unlink($path);
        }
    }
}
