<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function descargar($pedido)
    {
        $pedido = Pedidos::with(['productos.producto'])->findOrFail($pedido);
        $pdf = Pdf::loadView('pdf.factura-pdf', compact('pedido'));
        return $pdf->download('Factura_TECNOBOX_Pedido_' . $pedido->id . '.pdf');
    }
} 