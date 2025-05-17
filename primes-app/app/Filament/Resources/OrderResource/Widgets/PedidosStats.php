<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Pedidos;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class PedidosStats extends BaseWidget
{
    protected function getStats(): array
    {
        $avg = Pedidos::query()->avg('total_general');
        return [
            Stat::make('Nuevos Pedidos', Pedidos::where('estado', 'nuevo')->count()),
            Stat::make('Procesamiento de Pedidos', Pedidos::where('estado', 'procesando')->count()),
            Stat::make('Pedido Enviado', Pedidos::where('estado', 'enviado')->count()),
            Stat::make(
                'Precio Promedio',
                $avg !== null ? Number::currency($avg, 'DOP') : 'N/A'
            ),
        ];
    }
}
