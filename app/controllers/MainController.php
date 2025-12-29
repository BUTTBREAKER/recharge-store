<?php
class MainController {
    public static function home() {
        Flight::render('home', [], 'content');
        Flight::render('layout', ['title' => 'Inicio - WinStore']);
    }

    public static function game($slug = 'mobile-legends') {
        $configModel = new SystemConfig();
        $exchangeRate = $configModel->getExchangeRate();
        
        Flight::render('game', ['exchangeRate' => $exchangeRate], 'content');
        Flight::render('layout', ['title' => 'Mobile Legends - SisifoStore']);
    }

    public static function checkout() {
        $data = Flight::request()->data;
        
        if (empty($data->player_id) || empty($data->paquete)) {
            Flight::redirect('/');
            return;
        }

        $pedidoModel = new Pedido();
        $pedidoId = $pedidoModel->crear([
            'juego' => $data->juego,
            'player_id' => $data->player_id,
            'server_id' => $data->server_id,
            'paquete' => $data->paquete,
            'monto' => $data->monto,
            'metodo_pago' => 'pagomovil', // Temporal, se actualiza en el siguiente paso
            'telefono' => $data->telefono
        ]);

        $pedido = $pedidoModel->obtenerPorId($pedidoId);
        Flight::render('checkout', ['pedido' => $pedido], 'content');
        Flight::render('layout', ['title' => 'Checkout - WinStore']);
    }

    public static function procesarPago() {
        $data = Flight::request()->data;
        $pedidoId = $data->pedido_id;
        $metodo = $data->metodo;

        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($pedidoId);

        if (!$pedido) {
            Flight::redirect('/');
            return;
        }

        // Actualizar método de pago final
        $db = Flight::db();
        $stmt = $db->prepare("UPDATE pedidos SET metodo_pago = ? WHERE id = ?");
        $stmt->execute([$metodo, $pedidoId]);

        if ($metodo === 'pagomovil') {
            Flight::redirect('/pago/pagomovil/' . $pedidoId);
        } else {
            Flight::redirect('/pago/binance/' . $pedidoId);
        }
    }

    public static function legal() {
        Flight::render('legal', [], 'content');
        Flight::render('layout', ['title' => 'Aviso Legal']);
    }

    public static function reembolsos() {
        Flight::render('reembolsos', [], 'content');
        Flight::render('layout', ['title' => 'Política de Reembolsos']);
    }

    /**
     * Notificaciones para usuarios
     */
    public static function notifications() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            Flight::redirect('/login');
            return;
        }
        
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorUsuario($_SESSION['user_id']);
        
        Flight::render('notifications', ['pedidos' => $pedidos], 'content');
        Flight::render('layout', ['title' => 'Mis Notificaciones']);
    }
}
