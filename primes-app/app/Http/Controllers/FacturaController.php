<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function generarPDF($id)
    {
        $pedido = Pedidos::with(['productos.producto', 'user', 'direccion'])->findOrFail($id);
        
        // Calcular totales
        $subtotal = $pedido->productos->sum('precio_total');
        $impuestos = $subtotal * 0.18;
        $envio = $pedido->costo_envio ?? 0;
        $total = $subtotal + $impuestos + $envio;

        $pdf = PDF::loadView('pdf.factura', [
            'pedido' => $pedido,
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'envio' => $envio,
            'total' => $total
        ]);

        return $pdf->download('factura-' . str_pad($pedido->id, 8, '0', STR_PAD_LEFT) . '.pdf');
    }
} 