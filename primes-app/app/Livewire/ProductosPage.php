<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

class ProductosPage extends Component
{
    use WithPagination;
    public $title = 'Productos - TECNOBOX';


    #[Url]
    public $selected_categorias = [];
    #[Url]
    public $selected_marcas = [];
    #[Url]
    public $selected_estado = [];
    #[Url]
    public $precio_min = 1000;
    #[Url]
    public $precio_max = 500000;
    
    public function render()
    {
        $productos = Producto::query()->where('esta_activo', 1);

        if (!empty($this->selected_categorias)) {
            $productos->whereIn('categoria_id', $this->selected_categorias);
        }
        if (!empty($this->selected_marcas)) {
            $productos->whereIn('marca_id', $this->selected_marcas);
        }
        if (!empty($this->selected_estado)) {
            if (in_array('en_stock', $this->selected_estado)) {
                $productos->where('en_stock', 1);
            }
            if (in_array('en_oferta', $this->selected_estado)) {
                $productos->where('en_oferta', 1);
            }
        }
        $productos->whereBetween('precio', [$this->precio_min, $this->precio_max]);

        return view('livewire.productos-page', [
            'productos' => $productos->paginate(6),
            'marcas' => Marca::where('esta_activa', 1)->get(['id', 'nombre', 'slug']),
            'categorias' => Categoria::where('esta_activa', 1)->get(['id', 'nombre', 'slug']),
        ])->title($this->title);
    }
}