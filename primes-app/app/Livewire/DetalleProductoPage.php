<?php

namespace App\Livewire;
use App\Models\Producto;

use Livewire\Component;

class DetalleProductoPage extends Component
{
    public $title = 'Detalle del Producto - TECNOBOX';
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }
    
    public function render()
    {
        return view('livewire.detalle-producto-page',[
            'producto' => Producto::where('slug', $this->slug)->firstOrFail()
        ])
            ->title($this->title);
    }
}
