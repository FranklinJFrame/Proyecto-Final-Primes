<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\ProductoReview;
use Illuminate\Support\Facades\Auth;

class ProductoReviewController extends Controller
{
    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        // Evitar doble reseña
        $yaComento = ProductoReview::where('producto_id', $producto->id)
            ->where('user_id', Auth::id())
            ->exists();
        if ($yaComento) {
            return back()->with('error', 'Ya has dejado una reseña para este producto.');
        }

        ProductoReview::create([
            'producto_id' => $producto->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comentario' => $request->comentario,
            'aprobado' => true, // Se publica de una vez
        ]);

        return back()->with('success', '¡Gracias por tu reseña! Se ha publicado correctamente.');
    }

    public function update(Request $request, Producto $producto, ProductoReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);
        $review->update([
            'rating' => $request->rating,
            'comentario' => $request->comentario,
        ]);
        return back()->with('success', '¡Reseña actualizada!');
    }

    public function destroy(Producto $producto, ProductoReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        $review->delete();
        return back()->with('success', '¡Reseña eliminada!');
    }
} 