<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Cancela un pedido.
     */
    public function cancelar(Request $request, Pedidos $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // Verificar si el pedido está en un estado cancelable ('nuevo' o 'procesando')
        if (!in_array($pedido->estado, ['nuevo', 'procesando'])) {
            return redirect()->back()->withErrors(['estado' => 'Este pedido no puede ser cancelado en su estado actual.']);
        }

        // Restaurar el stock de los productos
        foreach ($pedido->productos as $item) {
            $producto = Producto::find($item->producto_id);
            if ($producto) {
                $producto->cantidad += $item->cantidad;
                $producto->save();
            }
        }

        // Cambiar estado del pedido a 'cancelado'
        $pedido->estado = 'cancelado'; // Asumiendo que 'cancelado' es un estado válido en tu ENUM
        $pedido->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('pedidos.show', $pedido->id)->with('success', 'Pedido cancelado correctamente y stock restaurado.');
    }
} 