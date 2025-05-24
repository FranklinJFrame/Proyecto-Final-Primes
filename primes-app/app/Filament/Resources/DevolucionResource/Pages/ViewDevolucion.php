<?php

namespace App\Filament\Resources\DevolucionResource\Pages;

use App\Filament\Resources\DevolucionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDevolucion extends ViewRecord
{
    protected static string $resource = DevolucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
