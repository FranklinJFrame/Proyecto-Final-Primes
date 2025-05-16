<?php

namespace App\Livewire;

use Livewire\Component;

class CheckoutPage extends Component
{
    public $title = 'Finalizar Compra - TECNOBOX';
    
    public function render()
    {
        return view('livewire.checkout-page')
            ->title($this->title);
    }
}
