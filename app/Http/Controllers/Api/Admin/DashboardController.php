<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Models\Analytics;
use App\Models\Pedido;
use Flight;

/**
 * Estadísticas del dashboard admin (rol admin).
 * Replica los datos del AdminController::dashboard web (gráficos de ventas,
 * resumen, top productos y últimos pedidos) en JSON para el frontend.
 */
final class DashboardController
{
    /** GET /api/admin/dashboard — métricas + últimos pedidos. */
    public static function show(): void
    {
        $analyticsModel = new Analytics();

        $ultimosPedidos = array_map(
            [ApiOrderController::class, 'present'],
            new Pedido()->listarTodos([], 10),
        );

        Flight::json([
            'data' => [
                'ventas_diarias' => $analyticsModel->ventasDiarias(),
                'ventas_semanales' => $analyticsModel->ventasSemanales(),
                'ventas_mensuales' => $analyticsModel->ventasMensuales(),
                'resumen' => $analyticsModel->resumenVentas(),
                'top_productos' => $analyticsModel->topProductos(5),
                'ultimos_pedidos' => $ultimosPedidos,
            ],
        ]);
    }
}
