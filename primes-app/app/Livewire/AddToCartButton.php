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
    public $maxCantidad = 1;

    public function mount($productoId, $maxCantidad = 1)
    {
        $this->productoId = $productoId;
        $this->maxCantidad = $maxCantidad;
        if ($this->cantidad > $this->maxCantidad) {
            $this->cantidad = $this->maxCantidad;
        }
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            $this->dispatch('showLoginAlert');
            return;
        }

        if (!Auth::user()->hasVerifiedEmail()) {
            $this->feedback = 'Debes verificar tu correo electrónico antes de agregar productos al carrito. Por favor, revisa tu bandeja de entrada.';
            return;
        }

        $producto = Producto::find($this->productoId);
        if (!$producto) {
            $this->feedback = 'El producto ya no está disponible.';
            return;
        }

        $this->cantidad = max(1, min($this->cantidad, $producto->cantidad));
        if ($producto->cantidad <= 0) {
            $this->feedback = 'Producto agotado: El producto se agotó después de agregarlo al carrito.';
            return;
        }

        if ($this->cantidad > $producto->cantidad) {
            $this->feedback = 'No puedes agregar más de la cantidad disponible.';
            return;
        }

        $carrito = CarritoProducto::firstOrCreate([
            'user_id' => Auth::id(),
            'producto_id' => $producto->id,
        ], [
            'precio_unitario' => $producto->precio,
        ]);

        if (($carrito->cantidad + $this->cantidad) > $producto->cantidad) {
            $this->feedback = 'No puedes agregar más de la cantidad disponible en total (ya tienes parte en el carrito).';
            return;
        }

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
