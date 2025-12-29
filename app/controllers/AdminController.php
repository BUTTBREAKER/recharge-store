<?php

namespace RECHARGE\controllers;

use Flight;
use Leaf\Http\Session;
use RECHARGE\models\Analytics;
use RECHARGE\models\Pago;
use RECHARGE\models\PaymentConfig;
use RECHARGE\models\Pedido;
use RECHARGE\models\Producto;
use RECHARGE\models\SystemConfig;
use RECHARGE\models\User;

class AdminController
{
    public static function checkAdmin()
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            Flight::redirect('/login');

            exit;
        }
    }

    /**
     * Dashboard principal con analíticas y gráficos
     */
    public static function dashboard()
    {
        self::checkAdmin();

        $analyticsModel = new Analytics();
        $pedidoModel = new Pedido();

        // Obtener datos para gráficos
        $ventasDiarias = $analyticsModel->ventasDiarias();
        $ventasSemanales = $analyticsModel->ventasSemanales();
        $ventasMensuales = $analyticsModel->ventasMensuales();
        $resumen = $analyticsModel->resumenVentas();
        $topProductos = $analyticsModel->topProductos(5);

        // Últimos pedidos
        $ultimosPedidos = $pedidoModel->listarTodos(null, 10);

        Flight::render('admin/dashboard', [
            'ventasDiarias' => $ventasDiarias,
            'ventasSemanales' => $ventasSemanales,
            'ventasMensuales' => $ventasMensuales,
            'resumen' => $resumen,
            'topProductos' => $topProductos,
            'ultimosPedidos' => $ultimosPedidos
        ], 'content');
        Flight::render('layout', ['title' => 'Dashboard Admin - SisifoStore']);
    }

    /**
     * Gestión de precios de productos
     */
    public static function managePrices()
    {
        self::checkAdmin();

        $productoModel = new Producto();
        $juego = Flight::request()->query->juego ?? null;

        $productos = $productoModel->listarTodos($juego, false);

        Flight::render('admin/manage_prices', [
            'productos' => $productos,
            'juegoFiltro' => $juego
        ], 'content');
        Flight::render('layout', ['title' => 'Gestión de Precios - Admin']);
    }

    /**
     * Actualizar precio de un producto
     */
    public static function updatePrice()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        $precio = Flight::request()->data->precio;

        $productoModel = new Producto();
        $productoModel->actualizarPrecio($id, $precio);

        Flight::redirect('/admin/prices?success=1');
    }

    /**
     * Toggle activo/inactivo de producto
     */
    public static function toggleProduct()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;

        $productoModel = new Producto();
        $productoModel->toggleActivo($id);

        Flight::json(['success' => true]);
    }

    /**
     * Gestión de configuración de pagos
     */
    public static function managePayments()
    {
        self::checkAdmin();

        $paymentModel = new PaymentConfig();
        $configs = $paymentModel->obtenerTodas();

        $pagoMovil = null;
        $binance = null;

        foreach ($configs as $config) {
            if ($config['metodo'] === 'pagomovil') {
                $pagoMovil = $config;
            } elseif ($config['metodo'] === 'binance') {
                $binance = $config;
            }
        }

        Flight::render('admin/manage_payments', [
            'pagoMovil' => $pagoMovil,
            'binance' => $binance
        ], 'content');
        Flight::render('layout', ['title' => 'Configuración de Pagos - Admin']);
    }

    /**
     * Actualizar datos de pago
     */
    public static function updatePaymentData()
    {
        self::checkAdmin();

        $metodo = Flight::request()->data->metodo;
        $paymentModel = new PaymentConfig();

        if ($metodo === 'pagomovil') {
            $paymentModel->actualizarPagoMovil([
                'banco' => Flight::request()->data->banco,
                'telefono' => Flight::request()->data->telefono,
                'cedula' => Flight::request()->data->cedula,
                'titular' => Flight::request()->data->titular
            ]);
        } elseif ($metodo === 'binance') {
            $paymentModel->actualizarBinance([
                'merchant_id' => Flight::request()->data->merchant_id,
                'api_key' => Flight::request()->data->api_key,
                'instrucciones' => Flight::request()->data->instrucciones
            ]);
        }

        Flight::redirect('/admin/payments?success=1');
    }

    /**
     * Perfil del administrador
     */
    public static function profile()
    {
        self::checkAdmin();

        $userModel = new User();
        $user = $userModel->obtenerPorId(Session::get('user_id'));

        Flight::render('admin/admin_profile', ['user' => $user], 'content');
        Flight::render('layout', ['title' => 'Mi Perfil - Admin']);
    }

    /**
     * Actualizar perfil de administrador
     */
    public static function updateProfile()
    {
        self::checkAdmin();

        $userModel = new User();
        $userModel->actualizarPerfil(Session::get('user_id'), [
            'name' => Flight::request()->data->name,
            'email' => Flight::request()->data->email
        ]);

        Session::set('user_name', Flight::request()->data->name);
        Flight::redirect('/admin/profile?success=1');
    }

    /**
     * Cambiar contraseña de administrador
     */
    public static function changePassword()
    {
        self::checkAdmin();

        $currentPassword = Flight::request()->data->current_password;
        $newPassword = Flight::request()->data->new_password;

        $userModel = new User();
        $user = $userModel->obtenerPorId(Session::get('user_id'));

        if (password_verify($currentPassword, $user['password'])) {
            $userModel->cambiarPassword(Session::get('user_id'), $newPassword);
            Flight::redirect('/admin/profile?password_success=1');
        } else {
            Flight::redirect('/admin/profile?password_error=1');
        }
    }

    // Métodos existentes de gestión de pedidos
    public static function verPedido($id)
    {
        self::checkAdmin();
        $pedidoModel = new Pedido();
        $pagoModel = new Pago();

        $pedido = $pedidoModel->obtenerPorId($id);
        $pago = $pagoModel->obtenerPorPedido($id);

        Flight::render('admin/pedido_detalle', ['pedido' => $pedido, 'pago' => $pago], 'content');
        Flight::render('layout', ['title' => 'Detalle de Pedido']);
    }

    public static function actualizarEstado()
    {
        self::checkAdmin();
        $id = Flight::request()->data->id;
        $estado = Flight::request()->data->estado;

        $pedidoModel = new Pedido();
        $pedidoModel->actualizarEstado($id, $estado);

        Flight::redirect('/admin/orders?estado=' . $estado . '&success=updated');
    }

    // === Gestión de Pedidos/Recargas ===

    /**
     * Vista principal de gestión de pedidos
     */
    public static function ordersManagement()
    {
        self::checkAdmin();

        $filtro = Flight::request()->query->estado ?? 'pendiente';
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->listarTodos($filtro);

        // Contar por estado para badges
        $contadores = [
            'pendiente' => $pedidoModel->contarPorEstado('pendiente'),
            'confirmado' => $pedidoModel->contarPorEstado('confirmado'),
            'realizada' => $pedidoModel->contarPorEstado('realizada'),
            'cancelado' => $pedidoModel->contarPorEstado('cancelado')
        ];

        Flight::render('admin/orders_management', [
            'pedidos' => $pedidos,
            'filtro' => $filtro,
            'contadores' => $contadores
        ], 'content');
        Flight::render('layout', ['title' => 'Gestión de Recargas - Admin']);
    }

    /**
     * Verificar pago (pendiente → confirmado)
     */
    public static function verifyPayment($id)
    {
        self::checkAdmin();

        $pedidoModel = new Pedido();
        $pedidoModel->actualizarEstado($id, 'confirmado');

        Flight::redirect('/admin/orders?estado=confirmado&success=verified');
    }

    /**
     * Completar recarga (confirmado → realizada)
     */
    public static function completeOrder($id)
    {
        self::checkAdmin();

        $pedidoModel = new Pedido();
        $pedidoModel->actualizarEstado($id, 'realizada');

        Flight::redirect('/admin/orders?estado=realizada&success=completed');
    }

    /**
     * Rechazar pago (pendiente → cancelado)
     */
    public static function rejectPayment($id)
    {
        self::checkAdmin();

        $pedidoModel = new Pedido();
        $pedidoModel->actualizarEstado($id, 'cancelado');

        Flight::redirect('/admin/orders?estado=cancelado&success=rejected');
    }

    /**
     * Configuración de sistema (tasa de cambio)
     */
    public static function systemConfig()
    {
        self::checkAdmin();

        $configModel = new SystemConfig();
        $exchangeRate = $configModel->getExchangeRate();

        Flight::render('admin/system_config', [
            'exchangeRate' => $exchangeRate
        ], 'content');
        Flight::render('layout', ['title' => 'Configuración del Sistema - Admin']);
    }

    /**
     * Actualizar tasa de cambio
     */
    public static function updateExchangeRate()
    {
        self::checkAdmin();

        $rate = Flight::request()->data->exchange_rate;

        if ($rate && is_numeric($rate) && $rate > 0) {
            $configModel = new SystemConfig();
            $configModel->setExchangeRate($rate);
            Flight::redirect('/admin/config?success=1');
        } else {
            Flight::redirect('/admin/config?error=1');
        }
    }
}
