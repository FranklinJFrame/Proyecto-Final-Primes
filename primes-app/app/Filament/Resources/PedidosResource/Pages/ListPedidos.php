<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Filament\Resources\OrderResource\Widgets\PedidosStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPedidos extends ListRecords
{
    protected static string $resource = PedidosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            PedidosStats::class,
        ];
    }
}
