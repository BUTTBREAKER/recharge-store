<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Support\ApiTestCase;

/**
 * Contract tests for the /api/orders group (guest checkout + payment data).
 * Request bodies mirror odd/t8-suite.sh exactly (oracle for field names).
 */
final class OrdersTest extends ApiTestCase
{
    public function testCreateOrderAsGuestReturns201(): void
    {
        $res = $this->api->post('/api/orders', $this->guestOrder());

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('data', $res['body']);
        $this->assertGreaterThan(0, $res['body']['data']['id']);
        $this->assertNull($res['body']['data']['user_id']);
        $this->assertSame('pendiente', $res['body']['data']['estado']);
        $this->assertSame('123456789', $res['body']['data']['player_id']);
    }

    public function testCreateOrderWithBearerAssociatesUserReturns201(): void
    {
        $login = $this->loginResponse('test@test.com', 'password123');
        $token = (string) $login['token'];
        $userId = (int) $login['user']['id'];

        $res = $this->api->post(
            '/api/orders',
            [
                'juego' => 'Mobile Legends',
                'player_id' => '987654321',
                'server_id' => '4321',
                'paquete' => '172 Diamantes',
                'monto' => 3,
                'telefono' => '04147654321',
            ],
            $token,
        );

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame($userId, $res['body']['data']['user_id']);
    }

    public function testCreateOrderInvalidPlayerIdReturns422(): void
    {
        $res = $this->api->post('/api/orders', [
            'juego' => 'Mobile Legends',
            'player_id' => 'ABC',
            'server_id' => '1234',
            'paquete' => '86 Diamantes',
            'monto' => 1.51,
        ]);

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testCreateOrderMissingPackageAndAmountReturns422(): void
    {
        $res = $this->api->post('/api/orders', [
            'juego' => 'Mobile Legends',
            'player_id' => '123456789',
            'server_id' => '1234',
        ]);

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testPayOrderReturns200(): void
    {
        $orderId = $this->createGuestOrder();

        $res = $this->api->post("/api/orders/{$orderId}/pay", [
            'metodo' => 'pagomovil',
        ]);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame('pagomovil', $res['body']['data']['metodo']);
        $this->assertSame($orderId, $res['body']['data']['pedido']['id']);
        $this->assertArrayHasKey('beneficiary', $res['body']['data']);
    }

    public function testOrderStatusReturns200(): void
    {
        $orderId = $this->createGuestOrder();

        $res = $this->api->get("/api/orders/{$orderId}/status");

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame($orderId, $res['body']['data']['pedido']['id']);
        $this->assertArrayHasKey('pago', $res['body']['data']);
    }

    public function testOrderBinanceReturns200(): void
    {
        $orderId = $this->createGuestOrder();

        $res = $this->api->get("/api/orders/{$orderId}/binance");

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame($orderId, $res['body']['data']['pedido']['id']);
        $this->assertIsString($res['body']['data']['binance']['url']);
        $this->assertStringContainsString(
            'binance.com',
            $res['body']['data']['binance']['url'],
        );
    }

    public function testStatusOfUnknownOrderReturns404(): void
    {
        $res = $this->api->get('/api/orders/999999/status');

        $this->assertSame(404, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testUnknownApiRouteReturnsJson404(): void
    {
        $res = $this->api->get('/api/nope');

        $this->assertSame(404, $res['status']);
        $this->assertStringContainsString(
            'json',
            strtolower($res['contentType']),
        );
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    // ------------------------------------------------------------------

    /** Valid guest checkout payload straight from odd/t8-suite.sh. */
    private function guestOrder(): array
    {
        return [
            'juego' => 'Mobile Legends',
            'player_id' => '123456789',
            'server_id' => '1234',
            'paquete' => '86 Diamantes',
            'monto' => 1.51,
            'telefono' => '04141234567',
        ];
    }

    private function createGuestOrder(): int
    {
        $res = $this->api->post('/api/orders', $this->guestOrder());
        $this->assertSame(
            201,
            $res['status'],
            'Could not create the test order.',
        );
        $this->assertIsArray($res['body']);

        return (int) $res['body']['data']['id'];
    }
}
