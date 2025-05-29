<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos;
use App\Models\Devolucion;
use App\Models\DatosTarj;

class MiPedidosDetallePage extends Component
{
    public $title = 'Detalle de Pedido - TECNOBOX';
    
    public Pedidos $pedido;
    public $devoluciones;
    public ?DatosTarj $datosTarjetaPredeterminada = null;

    public function mount($order)
    {
        $this->pedido = Pedidos::with(['productos.producto', 'user', 'devoluciones'])
            ->where('id', $order)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $this->devoluciones = Devolucion::where('pedido_id', $this->pedido->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        
        if ($this->pedido->user) {
            $this->datosTarjetaPredeterminada = $this->pedido->user->defaultDatosTarjeta ?? $this->pedido->user->anyDatosTarjeta;
        }
    }

    public function render()
    {
        return view('livewire.mi-pedidos-detalle-page', [
            'pedido' => $this->pedido,
        ])->title($this->title);
    }

    public function cancelarPedido($pedidoId)
    {
        $pedido = Auth::user()->pedidos()->with('productos')->find($pedidoId);
        if (!$pedido) {
            session()->flash('error', 'Pedido no encontrado.');
            return;
        }
        if (!in_array($pedido->estado, ['nuevo', 'procesando'])) {
            session()->flash('error', 'Solo puedes cancelar pedidos que no han sido procesados o enviados.');
            return;
        }
        // Restaurar stock de los productos
        foreach ($pedido->productos as $item) {
            if ($item->producto) {
                $item->producto->cantidad += $item->cantidad;
                $item->producto->save();
            }
        }
        $pedido->estado = 'cancelado';
        $pedido->save();
        session()->flash('success', 'Pedido cancelado correctamente.');
        // Redirigir a la lista de pedidos o recargar
        return redirect()->route('pedidos');
    }
}
