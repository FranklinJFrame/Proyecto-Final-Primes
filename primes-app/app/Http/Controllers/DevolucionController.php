<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\Pedidos;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Para manejar la subida de imágenes

class DevolucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Pedidos $pedido)
    {
        // Asegurarse de que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== Auth::id()) {
            abort(403, 'No autorizado para acceder a este pedido.');
        }

        // Asegurarse de que el pedido ha sido entregado
        if ($pedido->estado !== 'entregado') {
            // Podrías redirigir con un mensaje de error
            return redirect()->back()->withErrors(['estado' => 'Solo puedes devolver productos de pedidos entregados.']);
        }

        // Aquí necesitarás pasar los productos del pedido a la vista
        // para que el cliente pueda seleccionar cuáles devolver.
        $pedido->load('productos.producto'); // Cargar productos y el detalle del producto

        return view('devoluciones.create', compact('pedido'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'productos_a_devolver' => 'required|array',
            'productos_a_devolver.*.pedido_producto_id' => 'required|exists:pedido_productos,id',
            'productos_a_devolver.*.cantidad' => 'integer|min:0',
            'motivo' => 'required|string|max:1000',
            'imagenes_devolucion' => 'nullable|array',
            'imagenes_devolucion.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'productos_a_devolver.required' => 'Debes especificar los productos a devolver.',
            'motivo.required' => 'Por favor, indica el motivo de la devolución.',
            'motivo.max' => 'El motivo de la devolución no debe exceder los 1000 caracteres.',
            'imagenes_devolucion.*.image' => 'Los archivos adjuntos deben ser imágenes.',
            'imagenes_devolucion.*.max' => 'Las imágenes no deben exceder 2MB.'
        ]);

        $pedido = Pedidos::findOrFail($request->pedido_id);

        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // Verificar si el pedido está en estado 'entregado'
        if ($pedido->estado !== 'entregado') {
            return redirect()->back()->withErrors(['estado' => 'Solo se pueden devolver productos de pedidos entregados.'])->withInput();
        }
        
        try {
            // Filter products with quantity > 0
            $productosADevolver = collect($request->productos_a_devolver)->filter(function ($producto) {
                return isset($producto['cantidad']) && $producto['cantidad'] > 0;
            });

            if ($productosADevolver->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Debes especificar al menos un producto para devolver con una cantidad mayor a 0.'])->withInput();
            }
            
            $devolucion = Devolucion::create([
                'pedido_id' => $pedido->id,
                'user_id' => Auth::id(),
                'motivo' => $request->motivo,
                'estado' => 'pendiente'
            ]);

            foreach ($productosADevolver as $productoData) {
                $pedidoProducto = PedidoProducto::findOrFail($productoData['pedido_producto_id']);
                
                // Validate return quantity against purchased quantity
                if ($productoData['cantidad'] > $pedidoProducto->cantidad) {
                    throw new \Exception("La cantidad a devolver del producto {$pedidoProducto->producto->nombre} no puede ser mayor a la cantidad comprada ({$pedidoProducto->cantidad}).");
                }

                $devolucion->devolucionProductos()->create([
                    'pedido_producto_id' => $pedidoProducto->id,
                    'cantidad_devuelta' => $productoData['cantidad'],
                ]);
            }

            // Manejar la subida de imágenes si se proporcionan
            $rutasImagenesGuardadas = [];
            if ($request->hasFile('imagenes_devolucion')) {
                foreach ($request->file('imagenes_devolucion') as $imagen) {
                    $path = $imagen->store('devoluciones/' . $devolucion->id, 'public');
                    $rutasImagenesGuardadas[] = $path;
                }
                $devolucion->imagenes_adjuntas = $rutasImagenesGuardadas;
            }

            // Cambiar estado del pedido y guardar devolución
            $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
            $pedido->save();
            $devolucion->save();

            // Redirigir a mis pedidos con mensaje de éxito
            return redirect()->route('pedidos')->with('success', '¡Tu solicitud de devolución se ha enviado correctamente!');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al procesar tu solicitud: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Devuelve el número de devoluciones pendientes y las creadas hoy.
     */
    public function resumenDashboard()
    {
        $pendientes = \App\Models\Devolucion::where('estado', 'pendiente')->count();
        $hoy = now()->toDateString();
        $recientesHoy = \App\Models\Devolucion::whereDate('created_at', $hoy)->count();
        return response()->json([
            'pendientes' => $pendientes,
            'recientes_hoy' => $recientesHoy,
        ]);
    }
}
