<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Middleware\ApiAuth;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the HMAC stateless token contract of ApiAuth.
 *
 * TOKEN_SECRET is overridden per test so results never depend on .env.
 */
final class ApiAuthTest extends TestCase
{
    private const TEST_SECRET = 'unit-test-secret';

    /** @var string|mixed|null */
    private $previousSecret;

    private bool $hadSecret;

    protected function setUp(): void
    {
        $this->hadSecret = array_key_exists('TOKEN_SECRET', $_ENV);
        $this->previousSecret = $this->hadSecret ? $_ENV['TOKEN_SECRET'] : null;
        $_ENV['TOKEN_SECRET'] = self::TEST_SECRET;
    }

    protected function tearDown(): void
    {
        if ($this->hadSecret) {
            $_ENV['TOKEN_SECRET'] = $this->previousSecret;
        } else {
            unset($_ENV['TOKEN_SECRET']);
        }
    }

    public function test_issue_and_verify_roundtrip(): void
    {
        $token = ApiAuth::issueToken(['id' => 42, 'role' => 'user']);

        $payload = ApiAuth::verify($token);

        $this->assertIsArray($payload);
        $this->assertSame(42, $payload['uid']);
        $this->assertSame('user', $payload['role']);
        $this->assertGreaterThanOrEqual(time() + 604000, $payload['exp']);
        $this->assertLessThanOrEqual(time() + 605000, $payload['exp']);
        // Contract: the payload carries uid/role/exp — NOT the user row.
        $this->assertArrayNotHasKey('id', $payload);
    }

    public function test_admin_role_is_preserved(): void
    {
        $token = ApiAuth::issueToken(['id' => 7, 'role' => 'admin']);

        $payload = ApiAuth::verify($token);

        $this->assertIsArray($payload);
        $this->assertSame('admin', $payload['role']);
        $this->assertSame(7, $payload['uid']);
    }

    public function test_token_has_two_dot_separated_parts(): void
    {
        $token = ApiAuth::issueToken(['id' => 1, 'role' => 'user']);

        $this->assertCount(2, explode('.', $token));
    }

    /**
     * Oracle for the local crafting helpers: they must reproduce the
     * implementation byte-for-byte, otherwise the crafted-token tests
     * (expired, missing exp) could pass for the wrong reason.
     */
    public function test_local_crafting_helpers_match_issue_token(): void
    {
        $token = ApiAuth::issueToken(['id' => 11, 'role' => 'user']);
        [$body, $signature] = explode('.', $token);

        $payload = base64_decode(strtr($body, '-_', '+/'), true);
        $this->assertIsString($payload);

        $this->assertSame($body, self::base64UrlEncode($payload));
        $this->assertSame($signature, self::sign($payload));
    }

    public function test_expired_token_is_rejected(): void
    {
        $payload = json_encode([
            'uid' => 1,
            'role' => 'user',
            'exp' => time() - 10,
        ], JSON_UNESCAPED_SLASHES);
        $token = self::base64UrlEncode($payload) . '.' . self::sign($payload);

        $this->assertNull(ApiAuth::verify($token));
    }

    public function test_tampered_signature_is_rejected(): void
    {
        $token = ApiAuth::issueToken(['id' => 9, 'role' => 'user']);

        [$body, $signature] = explode('.', $token);

        // Flip the last character so it definitely differs.
        $last = substr($signature, -1);
        $flipped = $last === 'A' ? 'B' : 'A';
        $tampered = $body . '.' . substr($signature, 0, -1) . $flipped;

        $this->assertNotSame($signature, substr($tampered, strlen($body) + 1));
        $this->assertNull(ApiAuth::verify($tampered));
    }

    #[DataProvider('malformedTokenProvider')]
    public function test_malformed_tokens_are_rejected(string $token): void
    {
        $this->assertNull(ApiAuth::verify($token));
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function malformedTokenProvider(): array
    {
        return [
            'empty string' => [''],
            'no dot' => ['nodot'],
            'not base64 body' => ['a.b'],
        ];
    }

    public function test_payload_without_exp_is_rejected(): void
    {
        $payload = json_encode([
            'uid' => 3,
            'role' => 'user',
        ], JSON_UNESCAPED_SLASHES);
        $token = self::base64UrlEncode($payload) . '.' . self::sign($payload);

        $this->assertNull(ApiAuth::verify($token));
    }

    public function test_token_signed_with_other_secret_is_rejected(): void
    {
        $token = ApiAuth::issueToken(['id' => 5, 'role' => 'user']);
        $this->assertIsArray(ApiAuth::verify($token));

        $_ENV['TOKEN_SECRET'] = 'other';

        $this->assertNull(ApiAuth::verify($token));

        $_ENV['TOKEN_SECRET'] = self::TEST_SECRET;
    }

    // ------------------------------------------------------------------
    // Local copies of the ApiAuth signing primitives (same algorithm),
    // used to craft tokens that would be impossible to build otherwise.
    // ------------------------------------------------------------------

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function sign(string $data): string
    {
        return hash_hmac(
            'sha256',
            $data,
            (string) ($_ENV['TOKEN_SECRET'] ?? ''),
        );
    }
}
