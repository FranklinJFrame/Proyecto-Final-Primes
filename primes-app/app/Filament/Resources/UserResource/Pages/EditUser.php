<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if (!$this->record->wasChanged()) {
            Notification::make()
                ->title('La cuenta ya se encuentra en el estado solicitado.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('¡Cuenta actualizada correctamente!')
                ->success()
                ->send();
        }
    }
}
