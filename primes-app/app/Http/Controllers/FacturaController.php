<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    /**
     * Genera y descarga la factura en PDF para un pedido específico
     */
    public function generarPDF($id)
    {
        $pedido = Pedidos::with(['productos.producto', 'user.direccions', 'direccion'])->findOrFail($id);
        
        // Obtener la dirección principal del usuario si existe
        $direccionUsuario = null;
        if ($pedido->user && $pedido->user->direccions->count() > 0) {
            $direccionUsuario = $pedido->user->direccions->first();
        }
        
        // Calcular totales
        $subtotal = $pedido->productos->sum(function($item) {
            return $item->precio_unitario * $item->cantidad;
        });
        $impuestos = round($subtotal * 0.18, 2);
        $envio = $pedido->costo_envio ?? 0;
        $total = $subtotal + $impuestos + $envio;

        $pdf = PDF::loadView('pdf.factura', [
            'pedido' => $pedido,
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'envio' => $envio,
            'total' => $total,
            'direccionUsuario' => $direccionUsuario
        ]);

        return $pdf->download('factura-' . str_pad($pedido->id, 8, '0', STR_PAD_LEFT) . '.pdf');
    }
    
    /**
     * Alias para generarPDF para mantener compatibilidad con las rutas existentes
     */
    public function descargar($pedido)
    {
        return $this->generarPDF($pedido);
    }
}