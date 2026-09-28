<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Support\ApiTestCase;

/**
 * Contract tests for POST /api/login and POST /api/register
 * (oracle: odd/t8-suite.sh).
 *
 * The database is truncated + reseeded before each test, so the users
 * created by register() never leak into the next test.
 */
final class LoginTest extends ApiTestCase
{
    public function testLoginOkReturnsToken(): void
    {
        $res = $this->api->post('/api/login', [
            'email' => 'admin@sisifo.store',
            'password' => 'admin123',
        ]);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('token', $res['body']);
        $this->assertIsString($res['body']['token']);
        $this->assertNotSame('', $res['body']['token']);
        $this->assertArrayHasKey('expires_in', $res['body']);
        $this->assertArrayHasKey('user', $res['body']);
        $this->assertSame('admin@sisifo.store', $res['body']['user']['email']);
        $this->assertSame('admin', $res['body']['user']['role']);
    }

    public function testLoginWrongPasswordReturns401(): void
    {
        $res = $this->api->post('/api/login', [
            'email' => 'admin@sisifo.store',
            'password' => 'mala',
        ]);

        $this->assertSame(401, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testLoginUnknownEmailReturns401(): void
    {
        $res = $this->api->post('/api/login', [
            'email' => 'nobody@test.com',
            'password' => 'password123',
        ]);

        $this->assertSame(401, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testLoginMissingFieldsReturns422(): void
    {
        $res = $this->api->post('/api/login', []);

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testRegisterReturns201(): void
    {
        $res = $this->api->post('/api/register', [
            'name' => 'New Customer',
            'email' => 'new-customer@test.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ]);

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('token', $res['body']);
        $this->assertArrayHasKey('user', $res['body']);
        $this->assertSame('new-customer@test.com', $res['body']['user']['email']);
        $this->assertSame('user', $res['body']['user']['role']);
    }

    public function testRegisterDuplicateEmailReturns409(): void
    {
        $payload = [
            'name' => 'Duplicate Customer',
            'email' => 'duplicate@test.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ];

        $first = $this->api->post('/api/register', $payload);
        $this->assertSame(201, $first['status']);

        $second = $this->api->post('/api/register', $payload);
        $this->assertSame(409, $second['status']);
        $this->assertIsArray($second['body']);
        $this->assertArrayHasKey('message', $second['body']);
    }

    public function testRegisterWithoutConfirmPasswordReturns422(): void
    {
        $res = $this->api->post('/api/register', [
            'name' => 'No Confirm',
            'email' => 'no-confirm@test.com',
            'password' => 'password123',
        ]);

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }
}
