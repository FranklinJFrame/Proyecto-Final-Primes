<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos;

class MisPedidosPage extends Component
{
    public $title = 'Mis Pedidos - TECNOBOX';
    
    public $pedidos = [];

    public function mount()
    {
        $this->pedidos = Auth::user()->pedidos()->orderByDesc('created_at')->get();
    }

    public function render()
    {
        return view('livewire.mis-pedidos-page', [
            'pedidos' => $this->pedidos,
        ])->title($this->title);
    }
}
