<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordPage extends Component
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;
    public $status = null;

    public function mount()
    {
        $this->token = request()->query('token');
        $this->email = request()->query('email');
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->password = Hash::make($this->password);
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $this->status = '¡Contraseña restablecida! Ahora puedes iniciar sesión.';
            return redirect('/login');
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
