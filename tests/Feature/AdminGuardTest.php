<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Support\ApiTestCase;

/**
 * Contract tests for the /api/admin group guards (401/403) plus the order
 * transition and user management endpoints. Oracle: odd/t8-suite.sh.
 *
 * The harness server runs with API_AUTH_STRICT=true, so the guards below
 * exercise the production-like behaviour.
 */
final class AdminGuardTest extends ApiTestCase
{
    public function testAdminOrdersWithoutTokenReturns401(): void
    {
        $res = $this->api->get('/api/admin/orders');

        $this->assertSame(401, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminOrdersWithUserRoleReturns403(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $res = $this->api->get('/api/admin/orders', $token);

        $this->assertSame(403, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminOrdersWithAdminTokenReturns200(): void
    {
        $token = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->get('/api/admin/orders', $token);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertArrayHasKey('contadores', $res['body']);
    }

    public function testAdminVerifyAndCompleteOrderReturns200(): void
    {
        // Authed order: the transition must also insert the user's
        // notification row (notificaciones table).
        $orderId = $this->createOrderForTestUser();

        $verify = $this->api->post(
            "/api/admin/orders/{$orderId}/verify",
            [],
            $this->adminToken(),
        );
        $this->assertSame(200, $verify['status']);
        $this->assertIsArray($verify['body']);
        $this->assertSame(
            'confirmado',
            $verify['body']['data']['pedido']['estado'],
        );

        $complete = $this->api->post(
            "/api/admin/orders/{$orderId}/complete",
            [],
            $this->adminToken(),
        );
        $this->assertSame(200, $complete['status']);
        $this->assertIsArray($complete['body']);
        $this->assertSame(
            'realizada',
            $complete['body']['data']['pedido']['estado'],
        );
    }

    public function testAdminRejectUnknownOrderReturns404(): void
    {
        $res = $this->api->post(
            '/api/admin/orders/999999/reject',
            [],
            $this->adminToken(),
        );

        $this->assertSame(404, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminUpdateEstadoInvalidReturns422(): void
    {
        $orderId = $this->createOrderForTestUser();

        $res = $this->api->put(
            "/api/admin/orders/{$orderId}/estado",
            ['estado' => 'raro'],
            $this->adminToken(),
        );

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminCannotChangeOwnRoleReturns403(): void
    {
        $admin = $this->loginResponse('admin@sisifo.store', 'admin123');
        $adminId = (int) $admin['user']['id'];

        $res = $this->api->put(
            "/api/admin/users/{$adminId}/role",
            ['role' => 'user'],
            (string) $admin['token'],
        );

        $this->assertSame(403, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminCannotDeleteSelfReturns403(): void
    {
        $admin = $this->loginResponse('admin@sisifo.store', 'admin123');
        $adminId = (int) $admin['user']['id'];

        $res = $this->api->delete(
            "/api/admin/users/{$adminId}",
            (string) $admin['token'],
        );

        $this->assertSame(403, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testDeleteUserWithOrdersReturns409(): void
    {
        // The test user gets an order first (created through the API, exactly
        // like the frontend would), then the admin tries to delete them.
        $testUser = $this->loginResponse('test@test.com', 'password123');
        $testUserId = (int) $testUser['user']['id'];
        $this->createOrderForTestUser();

        $res = $this->api->delete(
            "/api/admin/users/{$testUserId}",
            $this->adminToken(),
        );

        $this->assertSame(409, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
        $this->assertArrayHasKey('pedidos_count', $res['body']);
    }

    public function testDeleteCleanUserReturns200(): void
    {
        $registered = $this->api->post('/api/register', [
            'name' => 'Disposable User',
            'email' => 'disposable@test.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ]);
        $this->assertSame(201, $registered['status']);
        $this->assertIsArray($registered['body']);
        $newUserId = (int) $registered['body']['user']['id'];

        $res = $this->api->delete(
            "/api/admin/users/{$newUserId}",
            $this->adminToken(),
        );

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testUpdateRoleInvalidRoleReturns422(): void
    {
        $testUser = $this->loginResponse('test@test.com', 'password123');
        $testUserId = (int) $testUser['user']['id'];

        $res = $this->api->put(
            "/api/admin/users/{$testUserId}/role",
            ['role' => 'zzz'],
            $this->adminToken(),
        );

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testUpdateRoleUnknownUserReturns404(): void
    {
        $res = $this->api->put(
            '/api/admin/users/999999/role',
            ['role' => 'user'],
            $this->adminToken(),
        );

        $this->assertSame(404, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    // ------------------------------------------------------------------

    private function adminToken(): string
    {
        return $this->loginAs('admin@sisifo.store', 'admin123');
    }

    /** Create an order owned by test@test.com and return its id. */
    private function createOrderForTestUser(): int
    {
        $testUser = $this->loginResponse('test@test.com', 'password123');

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
            (string) $testUser['token'],
        );

        $this->assertSame(
            201,
            $res['status'],
            'Could not create the test order.',
        );
        $this->assertIsArray($res['body']);

        return (int) $res['body']['data']['id'];
    }
}
