<?php

namespace App\Livewire;

use Livewire\Component;

class CancelPage extends Component
{
    public $title = 'Pedido Cancelado - TECNOBOX';
    
    public function render()
    {
        return view('livewire.cancel-page')
            ->title($this->title);
    }
}
