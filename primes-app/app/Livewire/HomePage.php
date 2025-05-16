<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Marca;

class HomePage extends Component
{
    public $title = 'Inicio - TECNOBOX';

    public function render()
    {
        $marcas = Marca::where('esta_activa', 1)->get();
        
        return view('livewire.home-page', [
            'marcas'=> $marcas
        ])
            ->title($this->title);
    }
}
