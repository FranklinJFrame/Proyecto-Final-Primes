<?php

namespace App\Livewire;

use Livewire\Component;

class SuccessPage extends Component
{
    public $title = 'Pedido Exitoso - TECNOBOX';
    
    public function render()
    {
        return view('livewire.success-page')
            ->title($this->title);
    }
}
