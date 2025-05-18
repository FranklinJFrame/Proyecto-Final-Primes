<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Categoria;

class HomePage extends Component
{
    public $title = 'Inicio - TECNOBOX';

    public function render()
    {
        $ofertas = \App\Models\Producto::where('en_oferta', 1)
            ->where('esta_activo', 1)
            ->select(['id','nombre','slug','imagenes','descripcion','precio','en_oferta'])
            ->limit(4)
            ->get();
        $marcas = cache()->remember('marcas_home', 3600, function() {
            return \App\Models\Marca::where('esta_activa', 1)
                ->select(['id','nombre','slug','imagen'])
                ->limit(4)
                ->get();
        });
        $categorias = cache()->remember('categorias_home', 3600, function() {
            return \App\Models\Categoria::where('esta_activa', 1)
                ->select(['id','nombre','slug','imagen'])
                ->limit(4)
                ->get();
        });
        $destacados = \App\Models\Producto::whereIn('id', [19, 20, 21])
            ->where('esta_activo', 1)
            ->select(['id','nombre','slug','imagenes','descripcion','precio'])
            ->get();
        return view('livewire.home-page', [
            'ofertas' => $ofertas,
            'marcas'=> $marcas,
            'categorias' => $categorias,
            'destacados' => $destacados
        ])
            ->title($this->title);
    }
}
