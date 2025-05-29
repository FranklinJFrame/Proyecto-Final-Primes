<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos;
use App\Models\CarritoProducto;

class MisPedidosPage extends Component
{
    use WithPagination;

    public $title = 'Mis Pedidos - TECNOBOX';
    
    public function mount()
    {
        // No need to store pedidos as a property when using pagination
    }

    public function comprarDeNuevo($pedidoId)
    {
        $pedido = Pedidos::with('productos.producto')->find($pedidoId);
        
        if (!$pedido) {
            session()->flash('error', 'Pedido no encontrado');
            return;
        }

        foreach ($pedido->productos as $item) {
            if ($item->producto) {
                // Check if product already exists in cart
                $existingCartItem = CarritoProducto::where('user_id', Auth::id())
                    ->where('producto_id', $item->producto->id)
                    ->first();

                if ($existingCartItem) {
                    // Update quantity if exists
                    $existingCartItem->cantidad += $item->cantidad;
                    $existingCartItem->save();
                } else {
                    // Create new cart item if doesn't exist
                    CarritoProducto::create([
                        'user_id' => Auth::id(),
                        'producto_id' => $item->producto->id,
                        'cantidad' => $item->cantidad,
                        'precio_unitario' => $item->precio_unitario
                    ]);
                }
            }
        }

        session()->flash('success', 'Productos agregados al carrito exitosamente');
        $this->redirect('/cart');
    }

    public function cancelarPedido($pedidoId)
    {
        $pedido = Auth::user()->pedidos()->with('productos')->find($pedidoId);
        if (!$pedido) {
            session()->flash('error', 'Pedido no encontrado.');
            return;
        }
        if (!in_array($pedido->estado, ['nuevo', 'procesando'])) {
            session()->flash('error', 'Solo puedes cancelar pedidos que no han sido procesados o enviados.');
            return;
        }
        // Restaurar stock de los productos
        foreach ($pedido->productos as $item) {
            if ($item->producto) {
                $item->producto->cantidad += $item->cantidad;
                $item->producto->save();
            }
        }
        $pedido->estado = 'cancelado';
        $pedido->save();
        session()->flash('success', 'Pedido cancelado correctamente y stock restaurado.');
    }

    public function solicitarDevolucion($pedidoId)
    {
        $pedido = Auth::user()->pedidos()->find($pedidoId);
        if (!$pedido) {
            session()->flash('error', 'Pedido no encontrado.');
            return;
        }
        if ($pedido->estado !== 'entregado') {
            session()->flash('error', 'Solo puedes solicitar devolución de pedidos entregados.');
            return;
        }
        // Aquí podrías crear una tabla/modelo de devoluciones, o simplemente marcar el pedido como "devolucion_solicitada"
        $pedido->notas = ($pedido->notas ? $pedido->notas."\n" : '') . '[Devolución solicitada el ' . now()->format('d/m/Y') . ']';
        $pedido->save();
        session()->flash('success', 'Solicitud de devolución enviada. Nuestro equipo te contactará.');
    }

    public function render()
    {
        $pedidos = Auth::user()->pedidos()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.mis-pedidos-page', [
            'pedidos' => $pedidos,
        ])->title($this->title);
    }
}
