<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Support\ApiTestCase;

/**
 * Contract tests for POST /api/payments/pagomovil (multipart upload).
 * Field names, statuses and the served-file pattern mirror
 * odd/t8-suite.sh: the response returns `data.comprobante` as an absolute
 * `/uploads/...` path, which is then fetched back over HTTP.
 *
 * Fixtures live outside the repo (public/ is read-only for tests); the
 * file the server stores under public/uploads is deleted by ApiTestCase's
 * snapshot cleanup after the test.
 */
final class PaymentsTest extends ApiTestCase
{
    private const FIXTURE_DIR = '/tmp/opencode/phpunit-fixtures';

    private const PNG_BASE64 =
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJ'
            . 'AAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    private string $pngFixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pngFixture = self::ensurePngFixture();
    }

    public function testPagomovilWithComprobanteReturns201AndServesFile(): void
    {
        $orderId = $this->createGuestOrder();

        $res = $this->api->postFile(
            '/api/payments/pagomovil',
            [
                'pedido_id' => $orderId,
                'referencia' => 'REF-PHASE3-001',
                'fecha' => '2026-09-28',
                'banco' => 'Banesco',
            ],
            ['comprobante' => $this->pngFixture],
        );

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $data = $res['body']['data'];
        $this->assertSame($orderId, $data['pedido_id']);
        $this->assertSame('REF-PHASE3-001', $data['referencia']);
        $this->assertSame('pendiente', $data['estado']);
        $this->assertStringStartsWith(
            '/uploads/',
            (string) $data['comprobante'],
        );
        $this->assertSame([$this->pngFixture], $this->api->sentFiles());

        // Fetch the served comprobante exactly like t8 does: GET the path
        // returned by the API.
        $served = $this->api->get($data['comprobante']);
        $this->assertSame(200, $served['status']);
        $this->assertStringContainsString(
            'image/png',
            strtolower($served['contentType']),
        );
        $this->assertIsString($served['body']);
        $this->assertNotSame('', $served['body']);
    }

    public function testPagomovilWithoutComprobanteReturns201(): void
    {
        // Optional by design (parity with the web form): no file stored,
        // `comprobante` comes back null.
        $orderId = $this->createGuestOrder();

        $res = $this->api->postFile('/api/payments/pagomovil', [
            'pedido_id' => $orderId,
            'referencia' => 'REF-PHASE3-002',
        ]);

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertNull($res['body']['data']['comprobante']);
        $this->assertSame('pendiente', $res['body']['data']['estado']);
    }

    public function testPagomovilWithBadExtensionReturns422(): void
    {
        $orderId = $this->createGuestOrder();

        // Same PNG bytes posted under a .txt name (t8: filename=mal.txt).
        $res = $this->api->postFile(
            '/api/payments/pagomovil',
            [
                'pedido_id' => $orderId,
                'referencia' => 'REF-PHASE3-003',
            ],
            [
                'comprobante' => [
                    'path' => $this->pngFixture,
                    'mime' => 'text/plain',
                    'name' => 'mal.txt',
                ],
            ],
        );

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        // Pin the extension branch specifically, not just any 422.
        $this->assertStringContainsString(
            'Formato de comprobante no permitido',
            (string) ($res['body']['message'] ?? ''),
        );
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function createGuestOrder(): int
    {
        $res = $this->api->post('/api/orders', [
            'juego' => 'Mobile Legends',
            'player_id' => '123456789',
            'server_id' => '1234',
            'paquete' => '86 Diamantes',
            'monto' => 1.51,
            'telefono' => '04141234567',
        ]);
        $this->assertSame(
            201,
            $res['status'],
            'Could not create the test order.',
        );
        $this->assertIsArray($res['body']);

        return (int) $res['body']['data']['id'];
    }

    /** A real 1x1 PNG outside the repo; created once per machine. */
    private static function ensurePngFixture(): string
    {
        $path = self::FIXTURE_DIR . '/comprobante.png';
        if (!is_file($path)) {
            if (
                !is_dir(self::FIXTURE_DIR)
                && !mkdir(self::FIXTURE_DIR, 0755, true)
                && !is_dir(self::FIXTURE_DIR)
            ) {
                self::fail('Could not create the fixture directory '
                . self::FIXTURE_DIR);
            }
            $bytes = base64_decode(self::PNG_BASE64, true);
            if (
                $bytes === false
                || file_put_contents($path, $bytes) === false
            ) {
                self::fail('Could not write the PNG fixture.');
            }
        }

        return $path;
    }
}
