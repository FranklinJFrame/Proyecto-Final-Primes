<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedidos;
use App\Models\DatosTarj;
use App\Models\Pagos;

class SuccessPage extends Component
{
    public $title = 'Pedido Exitoso - TECNOBOX';

    public function render()
    {
        $user = Auth::user();
        $pedido = null;
        $ultimos4 = null;

        if ($user) {
            $pedido = Pedidos::with(['productos.producto'])
                ->where('user_id', $user->id)
                ->latest('created_at')
                ->first();

            // Buscar los últimos 4 dígitos de la tarjeta usada en el pago
            if ($pedido && ($pedido->metodo_pago === 'tarjeta' || $pedido->metodo_pago === 'stripe')) {
                $pago = $pedido->pago;
                if ($pago) {
                    // Buscar la tarjeta usada por el usuario (por id o por predeterminada)
                    $tarjeta = DatosTarj::where('user_id', $user->id)
                        ->orderByDesc('es_predeterminada')
                        ->first();
                    $ultimos4 = $tarjeta && $tarjeta->numero_tarjeta ? substr($tarjeta->numero_tarjeta, -4) : null;
                }
            }
        }

        return view('livewire.success-page', [
            'pedido' => $pedido,
            'ultimos4' => $ultimos4,
        ])->title($this->title);
    }
}
