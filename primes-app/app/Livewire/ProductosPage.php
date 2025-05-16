<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;

class ProductosPage extends Component
{
    public $title = 'Productos - TECNOBOX';
    
    public function render()
    {
        $productos = Producto::where('esta_activo', 1)->get();

        return view('livewire.productos-page', [
            'productos' => $productos,
        ])->title($this->title);
    }
}
