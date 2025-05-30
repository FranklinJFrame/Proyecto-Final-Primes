<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\MetodosPago;
// use App\Models\Pagos;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;

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
        // $pedido = $this->record;
        
        // // Get the payment method by id
        // $metodoPago = MetodosPago::find($pedido->metodo_pago);
        
        // if ($metodoPago) {
        //     // Update or create the payment record
        //     // Pagos::updateOrCreate(  // Comentado porque la tabla 'pagos' no existe
        //     //     ['pedido_id' => $pedido->id],
        //     //     [
        //     //         'user_id' => $pedido->user_id,
        //     //         'metodo_pago_id' => $metodoPago->id,
        //     //         'estado' => $pedido->estado_pago,
        //     //         'monto' => $pedido->total_general,
        //     //         'moneda' => $pedido->moneda,
        //     //     ]
        //     // );
        // }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        $estadoActual = $record->estado;
        $estadoNuevo = $data['estado'] ?? $estadoActual;
        $orden = ['nuevo', 'procesando', 'enviado', 'entregado', 'cancelado'];
        $posActual = array_search($estadoActual, $orden);
        $posNuevo = array_search($estadoNuevo, $orden);

        // --- Validación de devolución activa ---
        $devolucionActiva = $record->devoluciones()
            ->whereIn('estado', ['pendiente', 'proceso de devolucion'])
            ->exists();
        if ($devolucionActiva && $estadoActual !== $estadoNuevo) {
            Notification::make()
                ->title('Pedido en disputa')
                ->body('El pedido tiene una devolución activa que impide cambios de estado.')
                ->danger()
                ->send();
            throw ValidationException::withMessages([
                'estado' => 'El pedido tiene una devolución activa que impide cambios de estado.'
            ]);
        }
        // --- Fin validación devolución activa ---

        $transicionesValidas = [
            'nuevo' => ['procesando', 'cancelado'],
            'procesando' => ['enviado', 'cancelado'],
            'enviado' => ['entregado'],
            // No se puede volver atrás ni saltar estados
        ];

        if ($estadoActual !== $estadoNuevo) {
            if (!isset($transicionesValidas[$estadoActual]) || !in_array($estadoNuevo, $transicionesValidas[$estadoActual])) {
                Notification::make()
                    ->title('Transición de estado no permitida')
                    ->body('No puedes cambiar el estado de ' . ucfirst($estadoActual) . ' a ' . ucfirst($estadoNuevo) . '. Sigue el flujo lógico del pedido.')
                    ->danger()
                    ->send();
                throw ValidationException::withMessages([
                    'estado' => 'Transición de estado no permitida.'
                ]);
            }
        }
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return parent::handleRecordUpdate($record, $data);
        } catch (\Illuminate\Database\QueryException $e) {
            Notification::make()
                ->title('Error de conexión')
                ->body('Pérdida de conexión al intentar guardar cambios. Intenta de nuevo.')
                ->danger()
                ->send();
            throw ValidationException::withMessages([
                'general' => 'Pérdida de conexión al intentar guardar cambios.'
            ]);
        }
    }
}
