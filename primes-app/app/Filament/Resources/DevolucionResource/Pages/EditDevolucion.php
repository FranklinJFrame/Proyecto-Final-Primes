<?php

namespace App\Filament\Resources\DevolucionResource\Pages;

use App\Filament\Resources\DevolucionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class EditDevolucion extends EditRecord
{
    protected static string $resource = DevolucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $original = $record->getOriginal();
        $user = $record->user;
        $soporte = 'franklin1903rp@protonmail.com';
        $state = $record->estado;
        $estadoAnterior = $original['estado'] ?? null;

        // Solo notificar si el estado cambió
        if ($state !== $estadoAnterior) {
            // Limpiar admin_notes si el estado NO es 'rechazada' ni 'recibido'
            if (!in_array($state, ['rechazada', 'recibido'])) {
                $record->admin_notes = null;
                $record->save();
            }

            $adminMsg = '';
            if (in_array($state, ['rechazada', 'recibido']) && !empty($record->admin_notes)) {
                $adminMsg = "\n\nMensaje del administrador: {$record->admin_notes}";
            }

            if ($user && $user->email) {
                $titulo = '';
                $mensaje = '';
                if ($state === 'pendiente') {
                    $titulo = 'Tu solicitud de devolución está en proceso de revisión';
                    $mensaje = "Hemos recibido tu solicitud de devolución. Nuestro equipo la está revisando y te notificaremos cuando tengamos una actualización.";
                } elseif ($state === 'recibido') {
                    $titulo = 'Tu producto ha sido recibido y está en proceso de revisión';
                    $mensaje = "Tu producto ha sido recibido por nuestro equipo. Ahora estamos analizando el estado del producto y verificando si cumple con los requisitos para la aprobación de la devolución." . $adminMsg;
                } elseif ($state === 'aprobada') {
                    $titulo = '¡Tu devolución ha sido aprobada!';
                    $mensaje = "¡Tu devolución ha sido aprobada! Pronto recibirás instrucciones para el reembolso o cambio de producto.\n\nGracias por confiar en nosotros.";
                } elseif ($state === 'rechazada') {
                    $titulo = 'Tu devolución no se ha podido completar';
                    $mensaje = "Lamentablemente, tu devolución no ha sido aprobada." . $adminMsg . "\n\nSi tienes dudas, contáctanos respondiendo a este correo.";
                }
                if ($titulo && $mensaje) {
                    // Notifica al usuario
                    \Mail::to($user->email)->send(new \App\Mail\DevolucionEstadoMail($record, $titulo, $mensaje, $soporte));
                    // Notifica a soporte si es nueva o rechazada
                    if ($state === 'pendiente' || $state === 'rechazada') {
                        $tituloSoporte = $state === 'pendiente'
                            ? 'Nueva solicitud de devolución recibida'
                            : 'Devolución rechazada - requiere atención';
                        $mensajeSoporte = $state === 'pendiente'
                            ? "Se ha recibido una nueva solicitud de devolución de {$user->name} (ID: #" . str_pad($record->id, 6, '0', STR_PAD_LEFT) . ")."
                            : "La devolución de {$user->name} (ID: #" . str_pad($record->id, 6, '0', STR_PAD_LEFT) . ") ha sido rechazada. Motivo: {$record->admin_notes}";
                        \Mail::to($soporte)->send(new \App\Mail\DevolucionEstadoMail($record, $tituloSoporte, $mensajeSoporte, $user->email));
                    }
                }
            }
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        $estadoActual = $record->estado;
        $estadoNuevo = $data['estado'] ?? $estadoActual;

        // No permitir pasar de 'pendiente' a 'aprobada' sin pasar por 'recibido'
        if ($estadoActual === 'pendiente' && $estadoNuevo === 'aprobada') {
            Notification::make()
                ->title('No puedes aprobar la devolución sin confirmar la recepción del producto')
                ->body('Primero debes cambiar el estado a "recibido" antes de aprobar el reembolso.')
                ->danger()
                ->send();
            throw ValidationException::withMessages([
                'estado' => 'No puedes aprobar la devolución sin confirmar la recepción del producto.'
            ]);
        }
        return $data;
    }
}
