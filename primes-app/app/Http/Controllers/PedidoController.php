<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function show(Pedidos $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pedidos.show', [
            'pedido' => $pedido->load(['productos.producto', 'pago', 'direccion']),
        ]);
    }
} 