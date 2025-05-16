<?php

namespace App\Livewire;

use Livewire\Component;

class CategoriesPage extends Component
{
    public $title = 'Categorías - TECNOBOX';
    
    public function render()
    {
        return view('livewire.categories-page')
            ->title($this->title);
    }
}
