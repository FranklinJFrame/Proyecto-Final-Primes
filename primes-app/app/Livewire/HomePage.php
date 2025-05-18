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
        $ofertas = Producto::where('en_oferta', 1)->where('esta_activo', 1)->inRandomOrder()->limit(4)->get();
        $marcas = Marca::where('esta_activa', 1)->inRandomOrder()->limit(4)->get();
        $categorias = Categoria::where('esta_activa', 1)->inRandomOrder()->limit(4)->get();
        return view('livewire.home-page', [
            'ofertas' => $ofertas,
            'marcas'=> $marcas,
            'categorias' => $categorias
        ])
            ->title($this->title);
    }
}
