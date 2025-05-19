<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartCounter extends Component
{
    public $count = 0;

    protected $listeners = ['cartUpdated' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = Auth::check() ? Auth::user()->carritoProductos()->sum('cantidad') : 0;
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
