<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoProducto;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Obtener todos los productos del carrito
    public function index()
    {
        $carrito = Auth::user()->carritoProductos()->with('producto')->get();
        $total = $carrito->sum(fn($item) => $item->precio_unitario * $item->cantidad);
        return response()->json(['carrito' => $carrito, 'total' => $total]);
    }

    // Agregar producto al carrito
    public function add(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'nullable|integer|min:1',
        ]);
        $producto = Producto::findOrFail($request->producto_id);
        if ($producto->cantidad < ($request->cantidad ?? 1)) {
            return response()->json(['error' => 'No hay suficiente stock.'], 422);
        }
        $carrito = CarritoProducto::firstOrCreate([
            'user_id' => Auth::id(),
            'producto_id' => $producto->id,
        ], [
            'precio_unitario' => $producto->precio,
        ]);
        $carrito->cantidad += $request->cantidad ?? 1;
        $carrito->precio_unitario = $producto->precio;
        $carrito->save();
        return response()->json(['success' => 'Producto agregado al carrito.']);
    }

    // Remover producto del carrito
    public function remove(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);
        $carrito = CarritoProducto::where('user_id', Auth::id())
            ->where('producto_id', $request->producto_id)
            ->first();
        if ($carrito) {
            $carrito->delete();
            return response()->json(['success' => 'Producto eliminado del carrito.']);
        }
        return response()->json(['error' => 'Producto no encontrado en el carrito.'], 404);
    }

    // Incrementar cantidad
    public function increment(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);
        $carrito = CarritoProducto::where('user_id', Auth::id())
            ->where('producto_id', $request->producto_id)
            ->first();
        if ($carrito) {
            $producto = Producto::find($request->producto_id);
            if ($producto->cantidad > $carrito->cantidad) {
                $carrito->cantidad++;
                $carrito->save();
                return response()->json(['success' => 'Cantidad incrementada.']);
            } else {
                return response()->json(['error' => 'No hay más stock disponible.'], 422);
            }
        }
        return response()->json(['error' => 'Producto no encontrado en el carrito.'], 404);
    }

    // Decrementar cantidad
    public function decrement(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);
        $carrito = CarritoProducto::where('user_id', Auth::id())
            ->where('producto_id', $request->producto_id)
            ->first();
        if ($carrito) {
            if ($carrito->cantidad > 1) {
                $carrito->cantidad--;
                $carrito->save();
                return response()->json(['success' => 'Cantidad decrementada.']);
            } else {
                $carrito->delete();
                return response()->json(['success' => 'Producto eliminado del carrito.']);
            }
        }
        return response()->json(['error' => 'Producto no encontrado en el carrito.'], 404);
    }

    // Vaciar carrito
    public function clear()
    {
        Auth::user()->carritoProductos()->delete();
        return response()->json(['success' => 'Carrito vaciado.']);
    }
}
