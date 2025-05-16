<?php

namespace App\Livewire;

use Livewire\Component;

class HomePage extends Component
{
    public $title = 'Inicio - TECNOBOX';

    public function render()
    {
        return view('livewire.home-page')
            ->title($this->title);
    }
}
