<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos;

class MiPedidosDetallePage extends Component
{
    public $title = 'Detalle de Pedido - TECNOBOX';
    
    public $pedido;

    public function mount($order)
    {
        $this->pedido = Auth::user()->pedidos()->with(['productos.producto', 'direccion', 'user.direccions'])->findOrFail($order);
    }

    public function render()
    {
        return view('livewire.mi-pedidos-detalle-page', [
            'pedido' => $this->pedido,
        ])->title($this->title);
    }
}
