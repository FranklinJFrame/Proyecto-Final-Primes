<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
    public $carrito = [];
    public $subtotal = 0;
    public $itbis = 0;
    public $envio = 0;
    public $total = 0;
    public $stripeError = '';
    public $editando_direccion = false;
    public $stripeIntent = null;
<<<<<<< HEAD
    public $paymentMethodId = null;
    public $card_number = '';
    public $card_expiry = '';
    public $card_cvc = '';
=======
    public $metodo_pago = 'paypal';
    public $datos_transferencia = '';
    public $datos_debito = '';
    public $banco_transferencia = '';
    public $cuenta_transferencia = '';
    public $referencia_transferencia = '';
    public $nombre_tarjeta = '';
    public $numero_tarjeta = '';
    public $cvc_tarjeta = '';
    public $vencimiento_tarjeta = '';
    public $errores_pago = [];

    protected $listeners = ['paypalPagoExitoso' => 'pagoPaypalExitoso'];
>>>>>>> b7e6f5b1eec792651d39af92f94888a752987a64

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

        // Crear intención de pago al cargar
        $this->createPaymentIntent();
    }

    protected function resetDireccionFields()
    {
        $this->nombre = '';
        $this->apellido = '';
        $this->telefono = '';
        $this->direccion_calle = '';
        $this->ciudad = '';
        $this->estado = '';
        $this->codigo_postal = '';
    }

    public function saveDireccion()
    {
        $this->validate([
            'nombre' => 'required|min:2',
            'apellido' => 'required|min:2',
            'telefono' => 'required|min:10',
            'direccion_calle' => 'required|min:5',
            'ciudad' => 'required',
            'estado' => 'required',
            'codigo_postal' => 'required',
        ], [
            'required' => 'Este campo es obligatorio',
            'min' => 'Este campo debe tener al menos :min caracteres',
        ]);

        if ($this->editando_direccion && $this->direccion_id) {
            $direccion = Auth::user()->direccions()->find($this->direccion_id);
            if ($direccion) {
                $direccion->update([
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'telefono' => $this->telefono,
                    'direccion_calle' => $this->direccion_calle,
                    'ciudad' => $this->ciudad,
                    'estado' => $this->estado,
                    'codigo_postal' => $this->codigo_postal,
                ]);
            }
        } else {
            $dir = Auth::user()->direccions()->create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'telefono' => $this->telefono,
                'direccion_calle' => $this->direccion_calle,
                'ciudad' => $this->ciudad,
                'estado' => $this->estado,
                'codigo_postal' => $this->codigo_postal,
            ]);
            $this->direccion_id = $dir->id;
        }

        $this->direcciones = Auth::user()->direccions()->get();
        $this->crear_nueva = false;
        $this->editando_direccion = false;
        $this->resetDireccionFields();
    }

    protected function createPaymentIntent()
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $amount = intval($this->total * 100);
            $this->stripeIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'dop',
                'payment_method_types' => ['card'],
                'description' => 'Pago en TECNOBOX',
                'metadata' => [
                    'user_id' => Auth::id(),
                    'total_items' => $this->carrito->sum('cantidad'),
                ],
            ]);
        } catch (\Exception $e) {
            $this->stripeError = $e->getMessage();
            return null;
        }
    }

    public function pagoPaypalExitoso($details)
    {
        $this->realizarPedido();
    }

    public function realizarPedido()
    {
        $this->errores_pago = [];
        if ($this->metodo_pago === 'tarjeta') {
            if (empty($this->nombre_tarjeta)) $this->errores_pago['nombre_tarjeta'] = 'El nombre es obligatorio';
            if (empty($this->numero_tarjeta)) $this->errores_pago['numero_tarjeta'] = 'El número de tarjeta es obligatorio';
            if (empty($this->cvc_tarjeta)) $this->errores_pago['cvc_tarjeta'] = 'El CVC es obligatorio';
            if (empty($this->vencimiento_tarjeta)) $this->errores_pago['vencimiento_tarjeta'] = 'La fecha de vencimiento es obligatoria';
        }
        if ($this->metodo_pago === 'transferencia') {
            if (empty($this->banco_transferencia)) $this->errores_pago['banco_transferencia'] = 'El banco es obligatorio';
            if (empty($this->cuenta_transferencia)) $this->errores_pago['cuenta_transferencia'] = 'El número de cuenta es obligatorio';
            if (empty($this->referencia_transferencia)) $this->errores_pago['referencia_transferencia'] = 'La referencia es obligatoria';
        }
        if (count($this->errores_pago) > 0) return;
        $user = Auth::user();
        if ($this->crear_nueva) {
            $this->saveDireccion();
        }
        $direccion = $user->direccions()->find($this->direccion_id);
        if (!$direccion) {
            session()->flash('error', 'Por favor, selecciona o crea una dirección válida.');
            return;
        }
        if ($this->carrito->isEmpty()) {
            session()->flash('error', 'Tu carrito está vacío.');
            return;
        }
<<<<<<< HEAD

        // Validar campos de tarjeta
        $this->validate([
            'card_number' => 'required|size:16',
            'card_expiry' => 'required|size:5',
            'card_cvc' => 'required|min:3|max:4',
        ], [
            'card_number.required' => 'El número de tarjeta es obligatorio',
            'card_number.size' => 'El número de tarjeta debe tener 16 dígitos',
            'card_expiry.required' => 'La fecha de vencimiento es obligatoria',
            'card_expiry.size' => 'La fecha debe tener el formato MM/YY',
            'card_cvc.required' => 'El código CVC es obligatorio',
            'card_cvc.min' => 'El CVC debe tener al menos 3 dígitos',
            'card_cvc.max' => 'El CVC no puede tener más de 4 dígitos',
        ]);

        try {
            // Crear el pedido
            $pedido = Pedidos::create([
                'user_id' => $user->id,
                'total_general' => $this->total,
                'metodo_pago' => 'tarjeta',
                'estado_pago' => 'pagado',
=======
        try {
            $estado_pago = 'pendiente';
            if ($this->metodo_pago === 'paypal') {
                $estado_pago = 'pagado';
            }
            $notas = null;
            if ($this->metodo_pago === 'transferencia') {
                $notas = 'Banco: ' . $this->banco_transferencia . ' | Cuenta: ' . $this->cuenta_transferencia . ' | Referencia: ' . $this->referencia_transferencia;
            } elseif ($this->metodo_pago === 'debito') {
                $notas = 'Nombre: ' . $this->nombre_tarjeta . ' | Tarjeta: ' . $this->numero_tarjeta . ' | CVC: ' . $this->cvc_tarjeta . ' | Vencimiento: ' . $this->vencimiento_tarjeta;
            }
            $pedido = Pedidos::create([
                'user_id' => $user->id,
                'total_general' => $this->total,
                'metodo_pago' => $this->metodo_pago,
                'estado_pago' => $estado_pago,
>>>>>>> b7e6f5b1eec792651d39af92f94888a752987a64
                'estado' => 'nuevo',
                'moneda' => 'DOP',
                'costo_envio' => $this->envio,
                'metodo_envio' => 'estandar',
                'notas' => $notas,
                'nombre' => $direccion->nombre,
                'apellido' => $direccion->apellido,
                'telefono' => $direccion->telefono,
                'direccion_calle' => $direccion->direccion_calle,
                'ciudad' => $direccion->ciudad,
                'estado_direccion' => $direccion->estado,
                'codigo_postal' => $direccion->codigo_postal,
<<<<<<< HEAD
=======
                'stripe_payment_intent' => null,
>>>>>>> b7e6f5b1eec792651d39af92f94888a752987a64
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
<<<<<<< HEAD

            // Limpiar el carrito
=======
>>>>>>> b7e6f5b1eec792651d39af92f94888a752987a64
            $user->carritoProductos()->delete();
            
            // Emitir evento con el ID del pedido
            $this->dispatch('pedidoCreado', $pedido->id);
            
        } catch (\Exception $e) {
<<<<<<< HEAD
            session()->flash('error', 'Hubo un error al procesar tu pedido. Por favor, intenta nuevamente.');
=======
            $this->stripeError = 'Hubo un error al procesar tu pago. Por favor, verifica los datos e intenta nuevamente.';
>>>>>>> b7e6f5b1eec792651d39af92f94888a752987a64
            return;
        }
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

    public function nuevaDireccion()
    {
        return redirect('/mi-cuenta');
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
