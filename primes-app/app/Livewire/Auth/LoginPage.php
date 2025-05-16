<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class LoginPage extends Component
{
    public $title = 'Iniciar Sesión - TECNOBOX';
    
    public function render()
    {
        return view('livewire.auth.login-page')
            ->title($this->title);
    }
}
