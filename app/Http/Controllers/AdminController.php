<?php

namespace App\Http\Controllers;

use Flight;
use Leaf\Http\Session;
use App\Models\Analytics;
use App\Models\Pago;
use App\Models\PaymentConfig;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\SystemConfig;
use App\Models\User;

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
        Flight::render('admin_layout', ['title' => 'Dashboard Admin - FearSold']);
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
        Flight::render('admin_layout', ['title' => 'Gestión de Precios - Admin']);
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
        Flight::render('admin_layout', ['title' => 'Configuración de Pagos - Admin']);
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
        Flight::render('admin_layout', ['title' => 'Mi Perfil - Admin']);
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

    // === CRUD de Productos ===

    /**
     * Vista para crear producto
     */
    public static function productCreate()
    {
        self::checkAdmin();

        // Obtener lista de juegos únicos
        $productoModel = new Producto();
        $productos = $productoModel->listarTodos(null, false);
        $juegos = array_unique(array_column($productos, 'juego'));
        
        // Agregar Mobile Legends por defecto si no hay productos
        if (empty($juegos)) {
            $juegos = ['Mobile Legends'];
        }

        Flight::render('admin/product_create', [
            'juegos' => $juegos
        ], 'content');
        Flight::render('admin_layout', ['title' => 'Nuevo Producto - Admin']);
    }

    /**
     * Guardar nuevo producto
     */
    public static function productStore()
    {
        self::checkAdmin();

        $data = Flight::request()->data;
        
        $productoModel = new Producto();
        $productoModel->crear([
            'juego' => $data->juego,
            'nombre' => $data->nombre,
            'cantidad' => $data->cantidad,
            'precio' => $data->precio,
            'precio_original' => $data->precio_original ?: null,
            'orden' => $data->orden ?: 0,
            'activo' => isset($data->activo) ? 1 : 0
        ]);

        Flight::redirect('/admin/prices?success=created');
    }

    /**
     * Vista para editar producto
     */
    public static function productEdit($id)
    {
        self::checkAdmin();

        $productoModel = new Producto();
        $producto = $productoModel->obtenerPorId($id);

        if (!$producto) {
            Flight::redirect('/admin/prices?error=not_found');
            return;
        }

        // Obtener lista de juegos únicos
        $productos = $productoModel->listarTodos(null, false);
        $juegos = array_unique(array_column($productos, 'juego'));

        Flight::render('admin/product_edit', [
            'producto' => $producto,
            'juegos' => $juegos
        ], 'content');
        Flight::render('admin_layout', ['title' => 'Editar Producto - Admin']);
    }

    /**
     * Actualizar producto completo
     */
    public static function productUpdate()
    {
        self::checkAdmin();

        $data = Flight::request()->data;
        
        $productoModel = new Producto();
        $productoModel->actualizar($data->id, [
            'juego' => $data->juego,
            'nombre' => $data->nombre,
            'cantidad' => $data->cantidad,
            'precio' => $data->precio,
            'precio_original' => $data->precio_original ?: null,
            'orden' => $data->orden ?: 0,
            'activo' => isset($data->activo) ? 1 : 0
        ]);

        Flight::redirect('/admin/products/edit/' . $data->id . '?success=1');
    }

    /**
     * Eliminar producto
     */
    public static function productDelete()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        
        $productoModel = new Producto();
        $productoModel->eliminar($id);

        Flight::redirect('/admin/prices?success=deleted');
    }

    // === CRUD de Juegos ===

    /**
     * Listado de juegos
     */
    public static function gamesIndex()
    {
        self::checkAdmin();

        $juegoModel = new \App\Models\Juego();
        $juegos = $juegoModel->listarTodos(false);

        Flight::render('admin/games_index', [
            'juegos' => $juegos
        ], 'content');
        Flight::render('admin_layout', ['title' => 'Gestión de Juegos - Admin']);
    }

    /**
     * Vista para crear juego
     */
    public static function gameCreate()
    {
        self::checkAdmin();

        Flight::render('admin/game_create', [], 'content');
        Flight::render('admin_layout', ['title' => 'Nuevo Juego - Admin']);
    }

    /**
     * Guardar nuevo juego
     */
    public static function gameStore()
    {
        self::checkAdmin();

        $data = Flight::request()->data;
        
        $juegoModel = new \App\Models\Juego();
        $juegoModel->crear([
            'nombre' => $data->nombre,
            'slug' => $data->slug ?: \App\Models\Juego::generarSlug($data->nombre),
            'descripcion' => $data->descripcion ?: null,
            'imagen' => $data->imagen ?: null,
            'icono' => $data->icono ?: '🎮',
            'orden' => $data->orden ?: 0,
            'activo' => isset($data->activo) ? 1 : 0
        ]);

        Flight::redirect('/admin/games?success=created');
    }

    /**
     * Vista para editar juego
     */
    public static function gameEdit($id)
    {
        self::checkAdmin();

        $juegoModel = new \App\Models\Juego();
        $juego = $juegoModel->obtenerPorId($id);

        if (!$juego) {
            Flight::redirect('/admin/games?error=not_found');
            return;
        }

        Flight::render('admin/game_edit', [
            'juego' => $juego
        ], 'content');
        Flight::render('admin_layout', ['title' => 'Editar Juego - Admin']);
    }

    /**
     * Actualizar juego
     */
    public static function gameUpdate()
    {
        self::checkAdmin();

        $data = Flight::request()->data;
        
        $juegoModel = new \App\Models\Juego();
        $juegoModel->actualizar($data->id, [
            'nombre' => $data->nombre,
            'slug' => $data->slug,
            'descripcion' => $data->descripcion ?: null,
            'imagen' => $data->imagen ?: null,
            'icono' => $data->icono ?: '🎮',
            'orden' => $data->orden ?: 0,
            'activo' => isset($data->activo) ? 1 : 0
        ]);

        Flight::redirect('/admin/games/edit/' . $data->id . '?success=1');
    }

    /**
     * Eliminar juego
     */
    public static function gameDelete()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        
        $juegoModel = new \App\Models\Juego();
        $juegoModel->eliminar($id);

        Flight::redirect('/admin/games?success=deleted');
    }

    /**
     * Toggle activo/inactivo de juego
     */
    public static function gameToggle()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        
        $juegoModel = new \App\Models\Juego();
        $juegoModel->toggleActivo($id);

        Flight::redirect('/admin/games');
    }

    // === CRUD de Usuarios ===

    /**
     * Listado de usuarios
     */
    public static function usersIndex()
    {
        self::checkAdmin();

        $filtro = Flight::request()->query->rol ?? null;
        $userModel = new User();
        
        $usuarios = $userModel->listarTodos($filtro);
        $contadores = [
            'total' => $userModel->contarPorRol(),
            'admin' => $userModel->contarPorRol('admin'),
            'user' => $userModel->contarPorRol('user')
        ];

        Flight::render('admin/users_index', [
            'usuarios' => $usuarios,
            'filtro' => $filtro,
            'contadores' => $contadores
        ], 'content');
        Flight::render('admin_layout', ['title' => 'Gestión de Usuarios - Admin']);
    }

    /**
     * Vista para editar usuario
     */
    public static function userEdit($id)
    {
        self::checkAdmin();

        $userModel = new User();
        $usuario = $userModel->obtenerPorId($id);

        if (!$usuario) {
            Flight::redirect('/admin/users?error=not_found');
            return;
        }

        // Contar pedidos del usuario
        $pedidoModel = new Pedido();
        $pedidosCount = 0;
        try {
            $pedidos = $pedidoModel->obtenerPorUsuario($id);
            $pedidosCount = count($pedidos);
        } catch (\Exception $e) {
            // Si falla, solo mostrar 0
        }

        Flight::render('admin/user_edit', [
            'usuario' => $usuario,
            'pedidosCount' => $pedidosCount
        ], 'content');
        Flight::render('admin_layout', ['title' => 'Editar Usuario - Admin']);
    }

    /**
     * Actualizar usuario
     */
    public static function userUpdate()
    {
        self::checkAdmin();

        $data = Flight::request()->data;
        
        $userModel = new User();
        // Solo permitimos actualizar el rol por políticas de privacidad
        $userModel->actualizarCompleto($data->id, [
            'role' => $data->role
        ]);

        Flight::redirect('/admin/users/edit/' . $data->id . '?success=1');
    }

    /**
     * Cambiar contraseña de usuario (admin)
     */
    /**
     * El cambio de contraseña por admin ha sido deshabilitado por privacidad
     */
    public static function userChangePassword()
    {
        self::checkAdmin();
        Flight::redirect('/admin/users');
    }

    /**
     * Hacer usuario administrador
     */
    public static function userMakeAdmin()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        
        $userModel = new User();
        $userModel->cambiarRol($id, 'admin');

        Flight::redirect('/admin/users?success=role_changed');
    }

    /**
     * Quitar permisos de admin
     */
    public static function userMakeUser()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        
        $userModel = new User();
        $userModel->cambiarRol($id, 'user');

        Flight::redirect('/admin/users?success=role_changed');
    }

    /**
     * Eliminar usuario
     */
    public static function userDelete()
    {
        self::checkAdmin();

        $id = Flight::request()->data->id;
        
        $userModel = new User();
        $userModel->eliminar($id);

        Flight::redirect('/admin/users?success=deleted');
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
        Flight::render('admin_layout', ['title' => 'Detalle de Pedido']);
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
        Flight::render('admin_layout', ['title' => 'Gestión de Recargas - Admin']);
    }

    /**
     * Verificar pago (pendiente → confirmado)
     */
    public static function verifyPayment($id)
    {
        self::checkAdmin();

        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);

        if ($pedido) {
            $pedidoModel->actualizarEstado($id, 'confirmado');

            // Generar notificación si el pedido tiene un user_id
            if (isset($pedido['user_id']) && $pedido['user_id']) {
                $notificacionModel = new \App\Models\Notificacion();
                $notificacionModel->crear(
                    $pedido['user_id'],
                    '✅ Pago Verificado',
                    "Tu pago por el paquete {$pedido['paquete']} ha sido verificado. Estamos procesando tu recarga.",
                    'pedido_actualizado',
                    '/notifications'
                );
            }
        }

        Flight::redirect('/admin/orders?estado=confirmado&success=verified');
    }

    /**
     * Completar recarga (confirmado → realizada)
     */
    public static function completeOrder($id)
    {
        self::checkAdmin();

        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);

        if ($pedido) {
            $pedidoModel->actualizarEstado($id, 'realizada');

            // Generar notificación
            if (isset($pedido['user_id']) && $pedido['user_id']) {
                $notificacionModel = new \App\Models\Notificacion();
                $notificacionModel->crear(
                    $pedido['user_id'],
                    '💎 Recarga Completada',
                    "¡Felicidades! Tu recarga de {$pedido['paquete']} ha sido enviada exitosamente. Revisa tu cuenta en el juego.",
                    'pedido_actualizado',
                    '/notifications'
                );
            }
        }

        Flight::redirect('/admin/orders?estado=realizada&success=completed');
    }

    /**
     * Rechazar pago (pendiente → cancelado)
     */
    public static function rejectPayment($id)
    {
        self::checkAdmin();

        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);

        if ($pedido) {
            $pedidoModel->actualizarEstado($id, 'cancelado');

            // Generar notificación
            if (isset($pedido['user_id']) && $pedido['user_id']) {
                $notificacionModel = new \App\Models\Notificacion();
                $notificacionModel->crear(
                    $pedido['user_id'],
                    '❌ Problema con el Pago',
                    "No pudimos verificar tu pago para el paquete {$pedido['paquete']}. Por favor, contacta a soporte.",
                    'pedido_actualizado',
                    '/notifications'
                );
            }
        }

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
        Flight::render('admin_layout', ['title' => 'Configuración del Sistema - Admin']);
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
