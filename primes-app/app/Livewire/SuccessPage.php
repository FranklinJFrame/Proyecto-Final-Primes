<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedidos;

class SuccessPage extends Component
{
    public $pedido;
    public $title = 'Pedido Completado - TECNOBOX';

    public function mount()
    {
        $pedidoId = request('pedido');
        $this->pedido = Pedidos::where('user_id', auth()->id())
                              ->where('id', $pedidoId)
                              ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.success-page')->title($this->title);
    }
}
