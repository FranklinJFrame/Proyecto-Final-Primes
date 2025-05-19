<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Direccion;
use App\Models\Pedidos;
use App\Models\PedidoProducto;
use App\Models\CarritoProducto;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutPage extends Component
{
    public $title = 'Finalizar Compra - TECNOBOX';
    
    public $direcciones = [];
    public $direccion_id = null;
    public $crear_nueva = false;
    public $nombre = '';
    public $apellido = '';
    public $telefono = '';
    public $direccion_calle = '';
    public $ciudad = '';
    public $estado = '';
    public $codigo_postal = '';
    public $metodo_pago = 'efectivo';
    public $carrito = [];
    public $subtotal = 0;
    public $itbis = 0;
    public $envio = 0;
    public $total = 0;
    public $stripeCard = false;
    public $stripeError = '';
    public $editando_direccion = false;

    public function mount()
    {
        $user = Auth::user();
        $this->direcciones = $user->direccions()->get();
        $this->carrito = $user->carritoProductos()->with('producto')->get();
        $this->subtotal = $this->carrito->sum(fn($item) => $item->precio_unitario * $item->cantidad);
        $this->itbis = round($this->subtotal * 0.18, 2);
        $cantidad_total = $this->carrito->sum('cantidad');
        $this->envio = $cantidad_total * 700;
        $this->total = $this->subtotal + $this->itbis + $this->envio;
        if ($this->direcciones->count()) {
            $this->direccion_id = $this->direcciones->first()->id;
        } else {
            $this->crear_nueva = true;
        }
    }

    public function updatedDireccionId($value)
    {
        $this->crear_nueva = ($value === 'nueva');
    }

    public function updatedMetodoPago($value)
    {
        $this->stripeCard = ($value === 'stripe');
    }

    public function saveDireccion()
    {
        $this->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'direccion_calle' => 'required',
            'ciudad' => 'required',
            'estado' => 'required',
            'codigo_postal' => 'required',
        ]);
        $dir = Auth::user()->direccions()->create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'telefono' => $this->telefono,
            'direccion_calle' => $this->direccion_calle,
            'ciudad' => $this->ciudad,
            'estado' => $this->estado,
            'codigo_postal' => $this->codigo_postal,
        ]);
        $this->direcciones = Auth::user()->direccions()->get();
        $this->direccion_id = $dir->id;
        $this->crear_nueva = false;
    }

    public function realizarPagoStripe($stripeToken)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $intent = PaymentIntent::create([
                'amount' => intval($this->total * 100), // Stripe usa centavos
                'currency' => 'dop',
                'payment_method_types' => ['card'],
                'description' => 'Pago en TECNOBOX',
            ]);
            return $intent;
        } catch (\Exception $e) {
            $this->stripeError = $e->getMessage();
            return null;
        }
    }

    public function realizarPedido($stripeToken = null)
    {
        $user = Auth::user();
        if ($this->crear_nueva) {
            $this->saveDireccion();
        }
        $direccion = $user->direccions()->find($this->direccion_id);
        if (!$direccion) {
            session()->flash('error', 'Selecciona o crea una dirección válida.');
            return;
        }
        if ($this->carrito->isEmpty()) {
            session()->flash('error', 'Tu carrito está vacío.');
            return;
        }
        $estado_pago = 'pendiente';
        if ($this->metodo_pago === 'stripe' || $this->metodo_pago === 'tarjeta') {
            // Simulación: siempre marcar como pagado
            $estado_pago = 'pagado';
        }
        $pedido = Pedidos::create([
            'user_id' => $user->id,
            'total_general' => $this->total,
            'metodo_pago' => $this->metodo_pago,
            'estado_pago' => $estado_pago,
            'estado' => 'nuevo',
            'moneda' => 'DOP',
            'costo_envio' => $this->envio,
            'metodo_envio' => 'estandar',
            'notas' => null,
            'nombre' => $direccion->nombre,
            'apellido' => $direccion->apellido,
            'telefono' => $direccion->telefono,
            'direccion_calle' => $direccion->direccion_calle,
            'ciudad' => $direccion->ciudad,
            'estado_direccion' => $direccion->estado,
            'codigo_postal' => $direccion->codigo_postal,
        ]);
        foreach ($this->carrito as $item) {
            PedidoProducto::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item->producto_id,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'precio_total' => $item->precio_unitario * $item->cantidad,
            ]);
        }
        $user->carritoProductos()->delete();
        return redirect('/success');
    }

    /**
     * Permite editar una dirección existente y precarga los campos en el formulario
     */
    public function editarDireccion($id = null)
    {
        $direccion = $this->direcciones->firstWhere('id', $id ?? $this->direccion_id);
        if ($direccion) {
            $this->nombre = $direccion->nombre;
            $this->apellido = $direccion->apellido;
            $this->telefono = $direccion->telefono;
            $this->direccion_calle = $direccion->direccion_calle;
            $this->ciudad = $direccion->ciudad;
            $this->estado = $direccion->estado;
            $this->codigo_postal = $direccion->codigo_postal;
            $this->direccion_id = $direccion->id;
            $this->editando_direccion = true;
            $this->crear_nueva = false;
        }
    }

    public function render()
    {
        return view('livewire.checkout-page', [
            'subtotal' => $this->subtotal,
            'itbis' => $this->itbis,
            'envio' => $this->envio,
            'total' => $this->total,
        ])->title($this->title);
    }
}
