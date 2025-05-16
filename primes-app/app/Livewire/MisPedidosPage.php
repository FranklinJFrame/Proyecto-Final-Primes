<?php

namespace App\Livewire;

use Livewire\Component;

class MisPedidosPage extends Component
{
    public $title = 'Mis Pedidos - TECNOBOX';
    
    public function render()
    {
        return view('livewire.mis-pedidos-page')
            ->title($this->title);
    }
}
