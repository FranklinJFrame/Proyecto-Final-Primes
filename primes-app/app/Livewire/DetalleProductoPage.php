<?php

namespace App\Livewire;
use App\Models\Producto;
use App\Models\ProductoReview;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class DetalleProductoPage extends Component
{
    public $title = 'Detalle del Producto - TECNOBOX';
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }
    
    public function render()
    {
        $producto = Producto::where('slug', $this->slug)->firstOrFail();
        $reviews = $producto->reviews()->where('aprobado', true)->with('user')->latest()->get();
        $puedeComentar = false;
        if (Auth::check()) {
            $yaComento = ProductoReview::where('producto_id', $producto->id)
                ->where('user_id', Auth::id())
                ->exists();
            $puedeComentar = !$yaComento;
        }
        return view('livewire.detalle-producto-page', [
            'producto' => $producto,
            'reviews' => $reviews,
            'puedeComentar' => $puedeComentar
        ])->title($this->title);
    }
}
