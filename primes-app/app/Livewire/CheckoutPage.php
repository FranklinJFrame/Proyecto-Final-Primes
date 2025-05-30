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
use App\Models\DatosTarj;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaMail;

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
    // public $stripeIntent = null; // NO USAR OBJETO COMPLEJO EN PROPIEDAD PUBLICA
    public $stripe_intent_id = null;
    public $stripe_client_secret = null;
    public $stripe_intent_status = null;
    public $metodo_pago = 'tarjeta';
    public $errores_pago = [];
    public $tarjetas = [];
    public $tarjeta_id = null;
    public $direccion_seleccionada = null;
    public $provinciasRD = [
        'Distrito Nacional' => 250,
        'Santo Domingo' => 250,
        'Santiago' => 350,
        'La Vega' => 350,
        'San Cristóbal' => 300,
        'Puerto Plata' => 400,
        'Duarte' => 350,
        'La Romana' => 400,
        'San Pedro de Macorís' => 400,
        'La Altagracia' => 500,
        'Peravia' => 350,
        'Azua' => 400,
        'Barahona' => 500,
        'San Juan' => 500,
        'Monseñor Nouel' => 350,
        'Monte Plata' => 350,
        'Valverde' => 400,
        'Sánchez Ramírez' => 350,
        'Espaillat' => 400,
        'María Trinidad Sánchez' => 400,
        'Hermanas Mirabal' => 400,
        'Samaná' => 500,
        'Bahoruco' => 500,
        'El Seibo' => 500,
        'Hato Mayor' => 500,
        'Independencia' => 600,
        'Pedernales' => 700,
        'Elías Piña' => 600,
        'Monte Cristi' => 500,
        'Dajabón' => 500,
        'San José de Ocoa' => 400,
        'Santiago Rodríguez' => 500,
        'San Juan' => 500,
    ];

    protected $listeners = ['paypalPagoExitoso' => 'pagoPaypalExitoso'];

    public function mount()
    {
        $user = Auth::user();
        $this->direcciones = $user->direccions()->get();
        $this->carrito = $user->carritoProductos()->with('producto')->get();
        $this->tarjetas = $user->tarjetas()->get();
        $this->subtotal = $this->carrito->sum(fn($item) => $item->precio_unitario * $item->cantidad);
        $this->itbis = round($this->subtotal * 0.18, 2);
        $this->envio = 0;
        if ($this->direcciones->count()) {
            $this->direccion_id = $this->direcciones->first()->id;
            $dir = $this->direcciones->first();
            if (isset($this->provinciasRD[$dir->estado])) {
                $this->envio = $this->provinciasRD[$dir->estado];
            }
        }
        $this->total = $this->subtotal + $this->itbis + $this->envio;
        $this->tarjeta_id = null;
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
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'dop',
                'payment_method_types' => ['card'],
                'description' => 'Pago en TECNOBOX',
                'metadata' => [
                    'user_id' => Auth::id(),
                    'total_items' => $this->carrito->sum('cantidad'),
                ],
            ]);

            $this->stripe_intent_id = $paymentIntent->id;
            $this->stripe_client_secret = $paymentIntent->client_secret;
            $this->stripe_intent_status = $paymentIntent->status;
            // NO GUARDAR $this->stripeIntent = $paymentIntent;
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
        $user = Auth::user();
        
        try {
            // Validar dirección seleccionada
            if (!$this->direccion_seleccionada) {
                session()->flash('error', 'Por favor, selecciona una dirección de envío.');
                return;
            }

            $direccion = $user->direccions()->find($this->direccion_seleccionada);
            if (!$direccion) {
                session()->flash('error', 'Por favor, selecciona una dirección válida.');
                return;
            }

            // Validar que hay productos en el carrito
            if ($this->carrito->isEmpty()) {
                session()->flash('error', 'Tu carrito está vacío.');
                return;
            }

            // Validar método de pago y datos relacionados
            if (!in_array($this->metodo_pago, ['paypal', 'tarjeta', 'pce'])) {
                session()->flash('error', 'Por favor, selecciona un método de pago válido.');
                return;
            }

            if ($this->metodo_pago === 'tarjeta') {
                if (!$this->tarjeta_id) {
                    session()->flash('error', 'Por favor, selecciona o ingresa los datos de una tarjeta válida.');
                    return;
                }
            }

            // Validar stock antes de crear el pedido
            $agotados = [];
            foreach ($this->carrito as $item) {
                if ($item->producto->cantidad < $item->cantidad) {
                    $agotados[] = $item->producto->nombre;
                }
            }
            if (count($agotados) > 0) {
                session()->flash('error', 'Los siguientes productos se agotaron durante el proceso de checkout: ' . implode(', ', $agotados));
                return;
            }

            // Crear el pedido
            $pedido = Pedidos::create([
                'user_id' => $user->id,
                'total_general' => $this->total,
                'metodo_pago' => $this->metodo_pago,
                'estado_pago' => 'pagado', // Siempre marcamos como pagado ya que no hay integración real con pasarelas de pago
                'estado' => 'nuevo',
                'moneda' => 'DOP',
                'costo_envio' => $this->envio,
                'metodo_envio' => 'tecnobox_transport',
                'notas' => null,
                'nombre' => $direccion->nombre,
                'apellido' => $direccion->apellido,
                'telefono' => $direccion->telefono,
                'direccion_calle' => $direccion->direccion_calle,
                'ciudad' => $direccion->ciudad,
                'estado_direccion' => $direccion->estado,
                'codigo_postal' => $direccion->codigo_postal,
                'stripe_payment_intent' => null,
            ]);

            // Crear los productos del pedido
            foreach ($this->carrito as $item) {
                PedidoProducto::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'precio_total' => $item->precio_unitario * $item->cantidad,
                ]);
                // Decrementar stock del producto
                if ($item->producto) {
                    $item->producto->decrement('cantidad', $item->cantidad);
                }
            }

            // Limpiar el carrito
            $user->carritoProductos()->delete();

            // Guardar el ID del pedido en la sesión
            session(['pedido_success_id' => $pedido->id]);

            // Enviar factura por correo automáticamente
            $direccionUsuario = $pedido->user && $pedido->user->direccions->count() > 0 ? $pedido->user->direccions->first() : null;
            $subtotal = $pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; });
            $impuestos = round($subtotal * 0.18, 2);
            $envio = $pedido->costo_envio ?? 0;
            $total = $subtotal + $impuestos + $envio;
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.factura', [
                'pedido' => $pedido,
                'subtotal' => $subtotal,
                'impuestos' => $impuestos,
                'envio' => $envio,
                'total' => $total,
                'direccionUsuario' => $direccionUsuario
            ]);
            Mail::to($pedido->user->email)->send(new FacturaMail($pedido, $pdf->output()));

            // Redirigir usando la URL completa
            return redirect()->route('/success');

        } catch (\Exception $e) {
            \Log::error('Error al crear pedido: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Hubo un error al procesar tu pedido: ' . $e->getMessage());
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

    public function updatedTarjetaId($value)
    {
        $this->rellenarDatosTarjeta($value);
    }

    public function rellenarDatosTarjeta($id)
    {
        $tarjeta = Auth::user()->tarjetas()->find($id);
        if ($tarjeta) {
            $this->tarjeta_id = $tarjeta->id;
        }
    }

    public function updatedDireccionSeleccionada($id)
    {
        $direccion = $this->direcciones->firstWhere('id', $id);
        if ($direccion && isset($this->provinciasRD[$direccion->estado])) {
            $this->envio = $this->provinciasRD[$direccion->estado];
        } else {
            $this->envio = 0;
        }
        $this->total = $this->subtotal + $this->itbis + $this->envio;
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
