<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;

class CategoriesPage extends Component
{
    public $title = 'Categorías - TECNOBOX';
    
    public function render()
    {
        $categorias = Categoria::where('esta_activa', 1)->get();
        return view('livewire.categories-page', [
            'categorias' => $categorias,
        ])->title($this->title);
    }
}
