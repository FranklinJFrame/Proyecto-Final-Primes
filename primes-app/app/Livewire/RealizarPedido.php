<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedidos;
use App\Models\Pagos;
use App\Models\MetodosPago;
use App\Models\CarritoProducto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaMail;

class RealizarPedido extends Component
{
    public $metodoPago;
    public $direccionId;
    public $moneda = 'DOP';
    public $notas;
    public $total;
    public $productos;

    protected $rules = [
        'metodoPago' => 'required|exists:metodos_pago,codigo',
        'direccionId' => 'required|exists:direccions,id',
        'moneda' => 'required|in:DOP,USD,EUR',
        'notas' => 'nullable|string',
    ];

    public function mount()
    {
        $this->productos = CarritoProducto::where('user_id', Auth::id())
            ->with('producto')
            ->get();

        $this->calcularTotal();
    }

    public function calcularTotal()
    {
        $this->total = $this->productos->sum(function ($item) {
            return $item->cantidad * $item->precio_unitario;
        });
    }

    public function realizarPedido()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Crear el pedido
            $pedido = Pedidos::create([
                'user_id' => Auth::id(),
                'total_general' => $this->total,
                'metodo_pago' => $this->metodoPago,
                'estado_pago' => 'pendiente',
                'estado' => 'nuevo',
                'moneda' => $this->moneda,
                'notas' => $this->notas,
            ]);

            // Agregar productos al pedido
            foreach ($this->productos as $item) {
                $pedido->productos()->create([
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'precio_total' => $item->cantidad * $item->precio_unitario,
                ]);

                // Actualizar stock del producto
                $item->producto->decrement('cantidad', $item->cantidad);
            }

            // Crear el registro de pago
            $metodoPago = MetodosPago::where('codigo', $this->metodoPago)->first();
            if ($metodoPago) {
                Pagos::create([
                    'pedido_id' => $pedido->id,
                    'user_id' => Auth::id(),
                    'metodo_pago_id' => $metodoPago->id,
                    'estado' => 'pendiente',
                    'monto' => $this->total,
                    'moneda' => $this->moneda,
                ]);
            }

            // Limpiar el carrito
            CarritoProducto::where('user_id', Auth::id())->delete();

            DB::commit();

            // Redirigir según el método de pago
            if ($this->metodoPago === 'card') {
                return redirect()->route('payment.stripe', ['pedido' => $pedido->id]);
            } elseif ($this->metodoPago === 'paypal') {
                return redirect()->route('payment.paypal', ['pedido' => $pedido->id]);
            } else {
                // Pago contra entrega
                session()->flash('message', 'Pedido realizado con éxito. Te contactaremos pronto.');

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

                return redirect()->route('pedidos.show', $pedido->id);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al procesar el pedido. Por favor, intenta de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.realizar-pedido', [
            'metodosPago' => MetodosPago::where('esta_activo', true)->get(),
            'direcciones' => Auth::user()->direccions,
        ]);
    }
} 