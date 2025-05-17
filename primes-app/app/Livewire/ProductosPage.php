<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class ProductosPage extends Component
{
    use WithPagination;
    public $title = 'Productos - TECNOBOX';
    
    public function render()
    {
        $productos = Producto::query()->where('esta_activo', 1);

        return view('livewire.productos-page', [
            'productos' => $productos->paginate(6),
            'marcas' => Marca::where('esta_activa', 1)->get(['id', 'nombre', 'slug']),
            'categorias' => Categoria::where('esta_activa', 1)->get(['id', 'nombre', 'slug']),
        ])->title($this->title);
    }
}