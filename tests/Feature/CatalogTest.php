<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\Support\ApiTestCase;

/**
 * Contract tests for the catalog: public /api/games + /api/config and the
 * admin product/game CRUD. Paths and payloads mirror odd/t8-suite.sh; the
 * `mobile-legends` slug and the products come from the seed baseline
 * (Tests\Support\Database::seed, values from the migrations).
 */
final class CatalogTest extends ApiTestCase
{
    // ------------------------------------------------------------------
    // Public catalog
    // ------------------------------------------------------------------

    public function testPublicGamesListReturnsSeededGames(): void
    {
        $res = $this->api->get('/api/games');

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertNotEmpty($res['body']['data']);

        $slugs = array_column($res['body']['data'], 'slug');
        $this->assertContains('mobile-legends', $slugs);
        foreach ($res['body']['data'] as $game) {
            $this->assertArrayHasKey('id', $game);
            $this->assertArrayHasKey('nombre', $game);
            $this->assertArrayHasKey('slug', $game);
        }
    }

    public function testPublicGameBySlugReturns200(): void
    {
        $res = $this->api->get('/api/games/mobile-legends');

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame('Mobile Legends', $res['body']['data']['nombre']);
        $this->assertSame('mobile-legends', $res['body']['data']['slug']);
    }

    public function testUnknownGameSlugReturns404(): void
    {
        $res = $this->api->get('/api/games/no-existe-abc');

        $this->assertSame(404, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testPublicGameProductsExposePrice(): void
    {
        $res = $this->api->get('/api/games/mobile-legends/products');

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertNotEmpty($res['body']['data']);
        foreach ($res['body']['data'] as $product) {
            $this->assertArrayHasKey('precio', $product);
            $this->assertGreaterThan(0, $product['precio']);
            $this->assertArrayHasKey('nombre', $product);
        }
    }

    public function testPublicExchangeRateReturnsRate(): void
    {
        $res = $this->api->get('/api/config/exchange-rate');

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        // Seeded value (see Database::seed) — deliberately NOT the 36.50
        // hardcoded fallback, so a broken DB read fails this assertion.
        $this->assertSame(42.5, (float) $res['body']['data']['exchange_rate_usd_bs']);
    }

    // ------------------------------------------------------------------
    // Admin: productos
    // ------------------------------------------------------------------

    public function testAdminProductsListReturns200(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->get('/api/admin/products', $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertNotEmpty($res['body']['data']);
        $this->assertArrayHasKey('precio', $res['body']['data'][0]);
    }

    public function testAdminCreatesProductReturns201(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->post('/api/admin/products', $this->newProduct(), $admin);

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertGreaterThan(0, $res['body']['data']['id']);
        $this->assertSame('T8 Item', $res['body']['data']['nombre']);
        $this->assertSame(2.25, $res['body']['data']['precio']);
        $this->assertTrue($res['body']['data']['activo']);
    }

    public function testAdminUpdatesProductPricePersists(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $productId = $this->createProduct($admin);

        $res = $this->api->put("/api/admin/products/{$productId}/price", ['precio' => 3.5], $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame(3.5, $res['body']['data']['precio']);

        // Persisted: re-read the product from the admin list.
        $list = $this->api->get('/api/admin/products', $admin);
        $this->assertSame(200, $list['status']);
        $this->assertSame(3.5, $this->productFrom($list, $productId)['precio']);
    }

    public function testAdminUpdatesProductPriceInvalidReturns422(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $productId = $this->createProduct($admin);

        $res = $this->api->put("/api/admin/products/{$productId}/price", ['precio' => -1], $admin);

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminTogglesProductReturns200(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $productId = $this->createProduct($admin);

        $res = $this->api->post("/api/admin/products/{$productId}/toggle", [], $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        // The response re-reads the row after the UPDATE: a flipped activo
        // here means the DB really stores it.
        $this->assertFalse($res['body']['data']['activo']);
    }

    public function testAdminUpdatesProductReturns200(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $productId = $this->createProduct($admin);

        $res = $this->api->put("/api/admin/products/{$productId}", ['nombre' => 'T8 Item v2'], $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame('T8 Item v2', $res['body']['data']['nombre']);
        $this->assertSame($productId, $res['body']['data']['id']);
    }

    public function testAdminDeletesProductThenReturns404(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $productId = $this->createProduct($admin);

        $delete = $this->api->delete("/api/admin/products/{$productId}", $admin);
        $this->assertSame(200, $delete['status']);
        $this->assertIsArray($delete['body']);
        $this->assertArrayHasKey('message', $delete['body']);

        $update = $this->api->put("/api/admin/products/{$productId}", ['nombre' => 'x'], $admin);
        $this->assertSame(404, $update['status']);
        $this->assertIsArray($update['body']);
        $this->assertArrayHasKey('message', $update['body']);
    }

    public function testAdminUnknownProductReturns404(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->put('/api/admin/products/999999', ['nombre' => 'x'], $admin);

        $this->assertSame(404, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    // ------------------------------------------------------------------
    // Admin: juegos
    // ------------------------------------------------------------------

    public function testAdminGamesListReturns200(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->get('/api/admin/games', $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertIsArray($res['body']['data']);
        $this->assertContains('mobile-legends', array_column($res['body']['data'], 'slug'));
    }

    public function testAdminCreatesGameWithoutActivoDefaultsActive(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->post('/api/admin/games', ['nombre' => 'T8 Game'], $admin);

        $this->assertSame(201, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertGreaterThan(0, $res['body']['data']['id']);
        $this->assertSame('T8 Game', $res['body']['data']['nombre']);
        $this->assertSame('t8-game', $res['body']['data']['slug']);
        $this->assertTrue($res['body']['data']['activo']);
    }

    public function testAdminGameUpdateWithoutActivoKeepsCurrentState(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $gameId = $this->createGame($admin);

        // t8: "admin game sin activo conserva" — a partial update that does
        // not send `activo` must not flip it.
        $res = $this->api->put("/api/admin/games/{$gameId}", ['icono' => '🚀'], $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertSame('🚀', $res['body']['data']['icono']);
        $this->assertTrue($res['body']['data']['activo']);
    }

    public function testAdminGameActivoFalseIsReallyStoredAsFalse(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $gameId = $this->createGame($admin);

        $res = $this->api->put("/api/admin/games/{$gameId}", ['activo' => false], $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertFalse($res['body']['data']['activo']);

        // Historical bug: `activo=false` stored as truthy. The public game
        // route 404s for inactive games, so a 404 here proves the row was
        // really written as false (not just cast in the JSON response).
        $public = $this->api->get('/api/games/t8-game');
        $this->assertSame(404, $public['status']);

        $list = $this->api->get('/api/admin/games', $admin);
        $this->assertSame(200, $list['status']);
        $listed = false;
        foreach ($list['body']['data'] as $game) {
            if ($game['id'] === $gameId) {
                $this->assertFalse($game['activo']);
                $listed = true;
            }
        }
        $this->assertTrue($listed, 'Updated game missing from the admin list.');
    }

    public function testAdminCreatesGameWithoutNameReturns422(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');

        $res = $this->api->post('/api/admin/games', ['nombre' => ''], $admin);

        $this->assertSame(422, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);
    }

    public function testAdminDeletesGameReturns200(): void
    {
        $admin = $this->loginAs('admin@sisifo.store', 'admin123');
        $gameId = $this->createGame($admin);

        $res = $this->api->delete("/api/admin/games/{$gameId}", $admin);

        $this->assertSame(200, $res['status']);
        $this->assertIsArray($res['body']);
        $this->assertArrayHasKey('message', $res['body']);

        $list = $this->api->get('/api/admin/games', $admin);
        $this->assertSame(200, $list['status']);
        $ids = array_column($list['body']['data'], 'id');
        $this->assertNotContains($gameId, $ids);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /** Create-product payload straight from odd/t8-suite.sh. */
    private function newProduct(): array
    {
        return [
            'juego' => 'Mobile Legends',
            'nombre' => 'T8 Item',
            'cantidad' => 5,
            'precio' => 2.25,
            'activo' => true,
        ];
    }

    private function createProduct(string $adminToken): int
    {
        $res = $this->api->post('/api/admin/products', $this->newProduct(), $adminToken);
        $this->assertSame(201, $res['status'], 'Could not create the test product.');
        $this->assertIsArray($res['body']);

        return (int) $res['body']['data']['id'];
    }

    private function createGame(string $adminToken): int
    {
        $res = $this->api->post('/api/admin/games', ['nombre' => 'T8 Game'], $adminToken);
        $this->assertSame(201, $res['status'], 'Could not create the test game.');
        $this->assertIsArray($res['body']);

        return (int) $res['body']['data']['id'];
    }

    /**
     * @param array{status: int, body: array|string, contentType: string} $list
     * @return array<string, mixed>
     */
    private function productFrom(array $list, int $productId): array
    {
        foreach ($list['body']['data'] as $product) {
            if ($product['id'] === $productId) {
                return $product;
            }
        }

        $this->fail("Product {$productId} missing from the admin list.");
    }
}
