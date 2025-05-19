<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Illuminate\Http\Request;

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
    #[Url]
    public $search = '';
    #[Url]
    public $ordenarPor = 'relevancia';
    
    public function updating($property)
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }
    
    public function mount(Request $request)
    {
        if ($request->has('categoria')) {
            $slug = $request->get('categoria');
            $cat = \App\Models\Categoria::where('slug', $slug)->first();
            if ($cat) {
                $this->selected_categorias = [strval($cat->id)];
            }
        }
        if ($request->has('marca')) {
            $slug = $request->get('marca');
            $marca = \App\Models\Marca::where('slug', $slug)->first();
            if ($marca) {
                $this->selected_marcas = [strval($marca->id)];
            }
        }
        if ($request->has('oferta')) {
            $this->selected_estado = ['en_oferta'];
        }
    }
    
    public function render()
    {
        $productos = Producto::query()
            ->where('esta_activo', 1)
            ->select(['id','nombre','slug','imagenes','descripcion','precio','moneda','cantidad','en_stock','en_oferta','categoria_id','marca_id']);

        if (!empty($this->search)) {
            $productos->where(function($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%')
                      ->orWhere('descripcion', 'like', '%'.$this->search.'%');
            });
        }
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

        // Lógica de ordenamiento
        switch ($this->ordenarPor) {
            case 'precio_asc':
                $productos->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $productos->orderBy('precio', 'desc');
                break;
            case 'recientes':
                $productos->orderBy('created_at', 'desc');
                break;
            case 'relevancia':
            default:
                $productos->orderByDesc('en_oferta')->orderByDesc('en_stock')->orderBy('created_at', 'desc');
                break;
        }

        return view('livewire.productos-page', [
            'productos' => $productos->with(['categoria:id,nombre,slug','marca:id,nombre,slug'])->paginate(6),
            'marcas' => cache()->remember('marcas_productos', 3600, function() {
                return Marca::where('esta_activa', 1)->get(['id', 'nombre', 'slug']);
            }),
            'categorias' => cache()->remember('categorias_productos', 3600, function() {
                return Categoria::where('esta_activa', 1)->get(['id', 'nombre', 'slug']);
            }),
            'ordenarPor' => $this->ordenarPor,
        ])->title($this->title);
    }
}