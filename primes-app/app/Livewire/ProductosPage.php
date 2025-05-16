<?php

namespace App\Livewire;

use Livewire\Component;

class ProductosPage extends Component
{
    public $title = 'Productos - TECNOBOX';
    
    public function render()
    {
        return view('livewire.productos-page')
            ->title($this->title);
    }
}
