<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Support\ApiTestCase;

/**
 * Contract tests for the /api/profile and /api/notifications groups.
 * Paths mirror odd/t8-suite.sh; field names follow ProfileController —
 * notably the password change uses current_password / new_password /
 * confirm_password (t8's password / password_confirmation payload only
 * exercises the "both fields required" branch of the controller).
 */
final class ProfileTest extends ApiTestCase
{
    public function testShowProfileReturnsAuthenticatedUserData(): void
    {
        $login = $this->loginResponse('test@test.com', 'password123');
        $token = (string) $login['token'];

        $res = $this->api->get('/api/profile', $token);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $data = $res['body']['data'];
        $this->assertSame((int) $login['user']['id'], $data['id']);
        $this->assertSame('Test User', $data['name']);
        $this->assertSame('test@test.com', $data['email']);
        $this->assertSame('user', $data['role']);
        $this->assertArrayHasKey('avatar_url', $data);
    }

    public function testShowProfileWithoutTokenReturns401(): void
    {
        // requireUser() fires in both auth modes; the harness server runs
        // with API_AUTH_STRICT=true (production-like).
        $res = $this->api->get('/api/profile');

        $this->assertSame(401, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testUpdateProfilePersistsNameAndEmail(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $update = $this->api->put(
            '/api/profile',
            [
                'name' => 'Cliente Test',
                'email' => 'cliente-test@test.com',
            ],
            $token,
        );

        $this->assertSame(200, $update['status']);
        $this->assertIsArray($update['body']);
        $this->assertSame('Cliente Test', $update['body']['data']['name']);
        $this->assertSame(
            'cliente-test@test.com',
            $update['body']['data']['email'],
        );

        // Persisted: a fresh read must return the updated row.
        $fresh = $this->api->get('/api/profile', $token);
        $this->assertSame(200, $fresh['status']);
        $this->assertIsArray($fresh['body']);
        $this->assertSame('Cliente Test', $fresh['body']['data']['name']);
        $this->assertSame(
            'cliente-test@test.com',
            $fresh['body']['data']['email'],
        );
    }

    public function testUpdateProfileWithoutEmailReturns422(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $res = $this->api->put(
            '/api/profile',
            ['name' => 'Cliente Test'],
            $token,
        );

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testUpdatePasswordWithWrongCurrentPasswordReturns422(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $res = $this->api->put(
            '/api/profile/password',
            [
                'current_password' => 'incorrecta',
                'new_password' => 'nuevaclave123',
                'confirm_password' => 'nuevaclave123',
            ],
            $token,
        );

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertStringContainsString(
            'incorrecta',
            (string) $res['body']['message'],
        );
    }

    public function testUpdatePasswordThenLoginWithNewPassword(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $res = $this->api->put(
            '/api/profile/password',
            [
                'current_password' => 'password123',
                'new_password' => 'nuevaclave123',
                'confirm_password' => 'nuevaclave123',
            ],
            $token,
        );

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);

        // The new password really works; the next test reseeds the database,
        // so no restoration is needed here.
        $login = $this->loginResponse('test@test.com', 'nuevaclave123');
        $this->assertArrayHasKey('token', $login);
    }

    public function testProfileOrdersReturnsOrderShape(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $created = $this->api->post(
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
        $this->assertSame(201, $created['status']);

        $res = $this->api->get('/api/profile/orders', $token);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertNotEmpty($res['body']['data']);
        $order = $res['body']['data'][0];
        $this->assertArrayHasKey('id', $order);
        $this->assertArrayHasKey('juego', $order);
        $this->assertArrayHasKey('estado', $order);
        $this->assertSame('Mobile Legends', $order['juego']);
    }

    public function testNotificationsListReturnsUnreadCount(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $res = $this->api->get('/api/notifications', $token);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertNotEmpty($res['body']['data']);
        // The seed inserts exactly one unread notification for this user.
        $this->assertSame(1, (int) $res['body']['unread']);
        $notification = $res['body']['data'][0];
        $this->assertArrayHasKey('titulo', $notification);
        $this->assertArrayHasKey('leido', $notification);
        $this->assertFalse($notification['leido']);
    }

    public function testNotificationsReadAllMarksThemRead(): void
    {
        $token = $this->loginAs('test@test.com', 'password123');

        $res = $this->api->post('/api/notifications/read-all', [], $token);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);

        $again = $this->api->get('/api/notifications', $token);
        $this->assertSame(200, $again['status']);
        $this->assertIsArray($again['body']);
        $this->assertSame(0, (int) $again['body']['unread']);
        foreach ($again['body']['data'] as $notification) {
            $this->assertTrue($notification['leido']);
        }
    }
}
