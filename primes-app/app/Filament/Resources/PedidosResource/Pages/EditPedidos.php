<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\MetodosPago;
use App\Models\Pagos;

class EditPedidos extends EditRecord
{
    protected static string $resource = PedidosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $pedido = $this->record;
        
        // Get the payment method by id
        $metodoPago = MetodosPago::find($pedido->metodo_pago);
        
        if ($metodoPago) {
            // Update or create the payment record
            Pagos::updateOrCreate(
                ['pedido_id' => $pedido->id],
                [
                    'user_id' => $pedido->user_id,
                    'metodo_pago_id' => $metodoPago->id,
                    'estado' => $pedido->estado_pago,
                    'monto' => $pedido->total_general,
                    'moneda' => $pedido->moneda,
                ]
            );
        }
    }
}
