<?php

namespace App\Livewire;

use Livewire\Component;

class MiPedidosDetallePage extends Component
{
    public $title = 'Detalle de Pedido - TECNOBOX';
    
    public function render()
    {
        return view('livewire.mi-pedidos-detalle-page')
            ->title($this->title);
    }
}
