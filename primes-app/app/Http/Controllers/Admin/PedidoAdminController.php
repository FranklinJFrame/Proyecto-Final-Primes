<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedidos;

class PedidoAdminController extends Controller
{
    public function show($pedido)
    {
        $pedido = Pedidos::with(['productos.producto'])->findOrFail($pedido);
        return view('admin.pedidos.factura', compact('pedido'));
    }
} 