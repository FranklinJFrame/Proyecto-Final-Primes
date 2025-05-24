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
}
