<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Filament\Resources\OrderResource\Widgets\PedidosStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
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

    public function getTabs(): array {
        return [
            null => Tab::make('Todos'),
            'Nuevo' => Tab::Make()->query(fn ($query) => $query->where('estado', 'nuevo')),
            'Procesando' => Tab::Make()->query(fn ($query) => $query->where('estado', 'procesando')),
            'Enviado' => Tab::Make()->query(fn ($query) => $query->where('estado', 'enviado')),
            'Cancelado' => Tab::Make()->query(fn ($query) => $query->where('estado', 'cancelado')),
        ];
    }
}
