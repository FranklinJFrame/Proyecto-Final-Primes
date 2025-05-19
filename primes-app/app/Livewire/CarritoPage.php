<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CarritoProducto;

class CarritoPage extends Component
{
    public $carrito = [];
    public $total = 0;
    public $title = 'Carrito de Compras - TECNOBOX';

    protected $listeners = ['cartUpdated' => 'refreshCarrito'];

    public function mount()
    {
        $this->refreshCarrito();
    }

    public function refreshCarrito()
    {
        if (Auth::check()) {
            $this->carrito = Auth::user()->carritoProductos()->with('producto')->get();
            $this->total = $this->carrito->sum(fn($item) => $item->precio_unitario * $item->cantidad);
        } else {
            $this->carrito = collect();
            $this->total = 0;
        }
    }

    public function increment($id)
    {
        $item = CarritoProducto::find($id);
        if ($item && $item->producto->cantidad > $item->cantidad) {
            $item->cantidad++;
            $item->save();
            $this->refreshCarrito();
            $this->dispatch('cartUpdated');
        }
    }

    public function decrement($id)
    {
        $item = CarritoProducto::find($id);
        if ($item) {
            if ($item->cantidad > 1) {
                $item->cantidad--;
                $item->save();
            } else {
                $item->delete();
            }
            $this->refreshCarrito();
            $this->dispatch('cartUpdated');
        }
    }

    public function remove($id)
    {
        $item = CarritoProducto::find($id);
        if ($item) {
            $item->delete();
            $this->refreshCarrito();
            $this->dispatch('cartUpdated');
        }
    }

    public function clear()
    {
        if (Auth::check()) {
            Auth::user()->carritoProductos()->delete();
            $this->refreshCarrito();
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        return view('livewire.carrito-page')
            ->title($this->title);
    }
}
