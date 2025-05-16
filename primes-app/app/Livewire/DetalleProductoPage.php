<?php

namespace App\Livewire;

use Livewire\Component;

class DetalleProductoPage extends Component
{
    public $title = 'Detalle del Producto - TECNOBOX';
    
    public function render()
    {
        return view('livewire.detalle-producto-page')
            ->title($this->title);
    }
}
