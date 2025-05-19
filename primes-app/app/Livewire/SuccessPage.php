<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos;

class SuccessPage extends Component
{
    public $title = 'Pedido Exitoso - TECNOBOX';
    
    public function render()
    {
        $user = Auth::user();
        $pedido = Pedidos::where('user_id', $user->id)->latest()->with('productos.producto')->first();
        return view('livewire.success-page', [
            'pedido' => $pedido,
        ])->title($this->title);
    }
}
