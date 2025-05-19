<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordPage extends Component
{
    public $email = '';
    public $status = null;

    public function forgotPassword()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $this->email)->first();
        if ($user) {
            Password::broker()->sendResetLink(['email' => $this->email]);
        }
        $this->status = 'Si el correo existe, se ha enviado un enlace para restablecer la contraseña.';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
