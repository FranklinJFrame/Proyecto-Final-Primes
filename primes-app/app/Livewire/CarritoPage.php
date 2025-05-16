<?php

namespace App\Livewire;

use Livewire\Component;

class CarritoPage extends Component
{
    public $title = 'Carrito de Compras - TECNOBOX';
    
    public function render()
    {
        return view('livewire.carrito-page')
            ->title($this->title);
    }
}
