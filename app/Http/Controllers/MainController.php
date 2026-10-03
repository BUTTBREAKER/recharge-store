<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\SystemConfig;
use Flight;
use flight\Container;
use Leaf\Db;
use Leaf\Http\Session;
use PDO;

class MainController
{
    public static function home(): void
    {
        Flight::render('pages/home', [], 'content');
        Flight::render('layout', ['title' => "Inicio - {$_ENV['APP_NAME']}"]);
    }

    public static function game(string $slug): void
    {
        $exchangeRate = Container::getInstance()->get(SystemConfig::class)->getExchangeRate();
        $db = Container::getInstance()->get(Db::class);
        $packages = $db->select('productos')->where('juego', '?')->bind(str_replace('-', ' ', $slug))->fetchAll();

        Flight::render('pages/game', compact('exchangeRate', 'packages', 'slug'), 'content');

        Flight::render('layout', [
            'title' => "Mobile Legends - {$_ENV['APP_NAME']}"
        ]);
    }

    public static function checkout()
    {
        $data = Flight::request()->data;

        // Validar Player ID y Server ID
        $validation = \App\Validators\PlayerIdValidator::validate($data->player_id, $data->server_id);
        if (!$validation['success']) {
            // Redirigir de vuelta al juego con el error
            $slug = $data->slug ?? 'mobile-legends';
            Flight::redirect("/juego/{$slug}?error=" . urlencode($validation['message']));
            return;
        }

        if (empty($data->player_id) || empty($data->paquete)) {
            Flight::redirect('/');
            return;
        }

        $pedidoModel = new Pedido();

        // Preparar datos del pedido
        $pedidoData = [
            'juego' => $data->juego,
            'player_id' => $data->player_id,
            'server_id' => $data->server_id,
            'paquete' => $data->paquete,
            'monto' => $data->monto,
            'metodo_pago' => 'pagomovil', // Temporal, se actualiza en el siguiente paso
            'telefono' => $data->telefono
        ];

        // Agregar user_id si el usuario está logueado
        if (Session::has('user_id')) {
            $pedidoData['user_id'] = Session::get('user_id');
        }

        $pedidoId = $pedidoModel->crear($pedidoData);

        $pedido = $pedidoModel->obtenerPorId($pedidoId);
        Flight::render('checkout', ['pedido' => $pedido], 'content');

        Flight::render('layout', [
            'title' => "Checkout - {$_ENV['APP_NAME']}"
        ]);
    }

    public static function procesarPago()
    {
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
        $db = Container::getInstance()->get(PDO::class);
        $stmt = $db->prepare("UPDATE pedidos SET metodo_pago = ? WHERE id = ?");
        $stmt->execute([$metodo, $pedidoId]);

        if ($metodo === 'pagomovil') {
            Flight::redirect('/pago/pagomovil/' . $pedidoId);
        } else {
            Flight::redirect('/pago/binance/' . $pedidoId);
        }
    }

    public static function legal()
    {
        Flight::render('legal', [], 'content');
        Flight::render('layout', ['title' => 'Aviso Legal']);
    }

    public static function reembolsos()
    {
        Flight::render('reembolsos', [], 'content');
        Flight::render('layout', ['title' => 'Política de Reembolsos']);
    }

    /**
     * Notificaciones para usuarios (Alertas Reales)
     */
    /**
     * Notificaciones para usuarios (Alertas Reales)
     */
    public static function notifications()
    {
        if (!Session::has('user_id')) {
            Flight::redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $notificacionModel = new \App\Models\Notificacion();

        // Marcar como leídas al entrar
        if (Flight::request()->query->read === 'all') {
            $notificacionModel->marcarTodasComoLeidas($userId);
            Flight::redirect('/notifications');
            return;
        }

        $notificaciones = $notificacionModel->obtenerPorUsuario($userId);

        // También obtener pedidos para referencia rápida (opcional, pero ayuda a la UI)
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->obtenerPorUsuario($userId, 5);

        Flight::render('notifications', [
            'notificaciones' => $notificaciones,
            'pedidos' => $pedidos
        ], 'content');

        Flight::render('layout', [
            'title' => "Mis Notificaciones - {$_ENV['APP_NAME']}"
        ]);
    }

    public static function soporte()
    {
        Flight::render('soporte', [], 'content');

        Flight::render('layout', [
            'title' => "Soporte - {$_ENV['APP_NAME']}"
        ]);
    }

    public static function terminos()
    {
        Flight::render('terminos', [], 'content');

        Flight::render('layout', [
            'title' => "Términos de Servicio - {$_ENV['APP_NAME']}"
        ]);
    }

    public static function privacidad()
    {
        Flight::render('privacidad', [], 'content');

        Flight::render('layout', [
            'title' => "Política de Privacidad - {$_ENV['APP_NAME']}"
        ]);
    }

    public static function notFound()
    {
        // Flight::render sin clave hace echo directo de la plantilla; fuera de
        // un route callback (sin ob_start) eso envía las cabeceras con 200
        // antes de que Response::send() pueda aplicar el 404. Por eso la
        // salida se construye dentro de la respuesta, y las rutas /api
        // responden JSON.
        if (str_starts_with(Flight::request()->url, '/api')) {
            Flight::json(['message' => 'Endpoint no encontrado.'], 404);
            return;
        }

        Flight::response()->status(404);
        Flight::render('404', [], 'content');

        Flight::render('layout', [
            'title' => "Página no encontrada - {$_ENV['APP_NAME']}"
        ], 'output');

        Flight::response()->write((string) Flight::view()->get('output'));
    }
}
