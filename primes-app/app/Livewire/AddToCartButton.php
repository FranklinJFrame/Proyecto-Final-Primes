<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\CarritoProducto;

class AddToCartButton extends Component
{
    public $productoId;
    public $cantidad = 1;
    public $feedback = null;

    public function addToCart()
    {
        if (!Auth::check()) {
            $this->dispatch('showLoginAlert');
            return;
        }
        $producto = Producto::find($this->productoId);
        if (!$producto || $producto->cantidad < $this->cantidad) {
            $this->feedback = 'No hay suficiente stock.';
            return;
        }
        $carrito = CarritoProducto::firstOrCreate([
            'user_id' => Auth::id(),
            'producto_id' => $producto->id,
        ], [
            'precio_unitario' => $producto->precio,
        ]);
        $carrito->cantidad += $this->cantidad;
        $carrito->precio_unitario = $producto->precio;
        $carrito->save();
        $this->feedback = '¡Producto añadido al carrito!';
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
