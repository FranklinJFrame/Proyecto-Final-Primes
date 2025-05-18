<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Marca;
use App\Models\Categoria;

class HomePage extends Component
{
    public $title = 'Inicio - TECNOBOX';

    public function render()
    {
        $marcas = Marca::where('esta_activa', 1)->get();
        $categorias = Categoria::where('esta_activa', 1)->get();
        
        return view('livewire.home-page', [
            'marcas'=> $marcas,
            'categorias' => $categorias
        ])
            ->title($this->title);
    }
}
