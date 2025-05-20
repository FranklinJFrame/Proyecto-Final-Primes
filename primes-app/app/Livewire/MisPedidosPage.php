<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos;
use App\Models\Carrito;

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
                $existingCartItem = Carrito::where('user_id', Auth::id())
                    ->where('producto_id', $item->producto->id)
                    ->first();

                if ($existingCartItem) {
                    // Update quantity if exists
                    $existingCartItem->cantidad += $item->cantidad;
                    $existingCartItem->save();
                } else {
                    // Create new cart item if doesn't exist
                    Carrito::create([
                        'user_id' => Auth::id(),
                        'producto_id' => $item->producto->id,
                        'cantidad' => $item->cantidad
                    ]);
                }
            }
        }

        session()->flash('success', 'Productos agregados al carrito exitosamente');
        $this->redirect('/cart');
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
