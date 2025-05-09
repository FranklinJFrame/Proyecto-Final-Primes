<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Pedidos;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PedidosStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nuevos pedidos', Pedidos::where('estado', 'new')->count()),
        ];
    }
}
