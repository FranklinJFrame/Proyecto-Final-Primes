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
            'productos_a_devolver' => 'required|array', // Esperamos un array de productos
            'productos_a_devolver.*.pedido_producto_id' => 'required|exists:pedido_productos,id',
            'productos_a_devolver.*.cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:1000',
            'imagenes_devolucion' => 'nullable|array',
            'imagenes_devolucion.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validar cada imagen
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
        
        // Iniciar transacción de base de datos por si algo falla
        
        try {
            
            $devolucion = Devolucion::create([
                'pedido_id' => $pedido->id,
                'user_id' => Auth::id(),
                'motivo' => $request->motivo,
                'estado' => 'pendiente', // Estado inicial
            ]);

            foreach ($request->productos_a_devolver as $productoData) {
                $pedidoProducto = PedidoProducto::findOrFail($productoData['pedido_producto_id']);
                
                // Validar que la cantidad a devolver no exceda la cantidad comprada
                if ($productoData['cantidad'] > $pedidoProducto->cantidad) {
                    // Podrías manejar este error de forma más específica
                    throw new \Exception("La cantidad a devolver del producto {$pedidoProducto->producto->nombre} excede la cantidad comprada.");
                }

                $devolucion->devolucionProductos()->create([
                    'pedido_producto_id' => $pedidoProducto->id,
                    'cantidad_devuelta' => $productoData['cantidad'],
                ]);
            }

            // Manejar la subida de imágenes si se proporcionan
            $rutasImagenesGuardadas = []; // Para guardar en la BD
            if ($request->hasFile('imagenes_devolucion')) {
                foreach ($request->file('imagenes_devolucion') as $imagen) {
                    // Guardar en storage/app/public/devoluciones/{devolucion_id}/{nombre_archivo_unico}
                    // Asegúrate de ejecutar `php artisan storage:link`
                    // Forma corregida: especificar el disco 'public' y la ruta dentro de ese disco.
                    $path = $imagen->store('devoluciones/' . $devolucion->id, 'public');
                    // $path ahora será 'devoluciones/ID/nombre_archivo.ext', que es lo que queremos.
                    $rutasImagenesGuardadas[] = $path; 
                }
                $devolucion->imagenes_adjuntas = $rutasImagenesGuardadas;
                // $devolucion->save(); // Se guarda junto con el estado del pedido más abajo
            }

            // Cambiar estado del pedido a 'proceso de devolucion' y guardar devolución con imágenes
            $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
            $pedido->save();
            $devolucion->save(); // Ahora guardamos la devolución después de asignar imágenes si las hay

            // Confirmar transacción
            

            // Redirigir al usuario a una página de confirmación o a sus pedidos
            // con un mensaje de éxito.
            return redirect()->route('pedidos.index')->with('success', 'Solicitud de devolución enviada correctamente.');

        } catch (\Exception $e) {
            // Revertir transacción
            
            // Redirigir con error
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
}
