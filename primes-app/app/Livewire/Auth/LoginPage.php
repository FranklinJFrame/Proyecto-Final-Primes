<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class LoginPage extends Component
{
    public $title = 'Iniciar Sesión - TECNOBOX';
    public $email = '';
    public $password = '';
    public $remember = false;

    public function render()
    {
        return view('livewire.auth.login-page')
            ->title($this->title);
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            session()->regenerate();
            return redirect('/'); // Redirige a la homepage tras login
        }

        $this->addError('email', 'Las credenciales no son válidas.');
    }
}
