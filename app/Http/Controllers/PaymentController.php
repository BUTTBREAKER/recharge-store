<?php

namespace App\Http\Controllers;

use Flight;
use App\Models\Pago;
use App\Models\Pedido;

class PaymentController
{
    public static function pagomovil($id)
    {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);
        if (!$pedido) {
            Flight::redirect('/');
        }

        Flight::render('pago_pagomovil', ['pedido' => $pedido], 'content');
        Flight::render('layout', ['title' => 'Pago Móvil - WinStore']);
    }

    public static function confirmarPagomovil()
    {
        $data = Flight::request()->data;
        $files = Flight::request()->files;

        $pedido_id = $data->pedido_id;
        $referencia = $data->referencia;

        $comprobante_path = '';
        if (isset($files['comprobante']) && $files['comprobante']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($files['comprobante']['name'], PATHINFO_EXTENSION);
            $filename = 'comp_' . $pedido_id . '_' . time() . '.' . $ext;
            $comprobante_path = 'uploads/' . $filename;
            move_uploaded_file($files['comprobante']['tmp_name'], __DIR__ . '/../../public/' . $comprobante_path);
        }

        $pagoModel = new Pago();
        $pagoModel->registrar([
            'pedido_id' => $pedido_id,
            'referencia' => $referencia,
            'comprobante' => $comprobante_path,
            'provider' => 'pagomovil'
        ]);

        Flight::redirect('/pago/estado/' . $pedido_id);
    }

    public static function binance($id)
    {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);
        if (!$pedido) {
            Flight::redirect('/');
        }

        // Aquí iría la lógica real de Binance Pay API
        // Por ahora simulamos la creación de una orden
        $binance_url = "https://pay.binance.com/checkout/simulado_" . $id;

        Flight::render('pago_binance', ['pedido' => $pedido, 'url' => $binance_url], 'content');
        Flight::render('layout', ['title' => 'Binance Pay - WinStore']);
    }

    public static function estado($id)
    {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->obtenerPorId($id);
        Flight::render('estado', ['pedido' => $pedido], 'content');
        Flight::render('layout', ['title' => 'Estado del Pedido']);
    }
}
