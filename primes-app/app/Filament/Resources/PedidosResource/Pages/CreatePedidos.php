<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\MetodosPago;
use App\Models\Pagos;

class CreatePedidos extends CreateRecord
{
    protected static string $resource = PedidosResource::class;

    protected function afterCreate(): void
    {
        $pedido = $this->record;
        
        // Get the payment method ID from the code
        $metodoPago = MetodosPago::where('codigo', $pedido->metodo_pago)->first();
        
        if ($metodoPago) {
            // Create the payment record
            Pagos::create([
                'pedido_id' => $pedido->id,
                'user_id' => $pedido->user_id,
                'metodo_pago_id' => $metodoPago->id,
                'estado' => $pedido->estado_pago,
                'monto' => $pedido->total_general,
                'moneda' => $pedido->moneda,
            ]);
        }
    }
}
