<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\PedidosResource;
use App\Models\Pedidos; // Agregado
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action; // Agregado

class LatestPedidos extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';
    protected static ? int $sort = 2;



    public function table(Table $table): Table
    {
        return $table
            ->query(PedidosResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            
            ->columns([
                TextColumn::make('id')
               ->label('Pedido ID')
               ->searchable(),

               TextColumn::make('user.name')
               ->searchable(),

               TextColumn::Make('total_general')
               ->money('USD'),

               TextColumn::make('estado')
               ->badge()
               ->color(fn(string $state):string => match ($state) {
                'nuevo' => 'info',
                'procesando' => 'warning',
                'enviado' => 'success',
                'entregado' => 'success',
                'cancelado' => 'danger',
               }) 
               ->icon(fn(string $state):string => match ($state) {
                'nuevo' => 'heroicon-n-sparkles',
                'procesando' => 'heroicon-m-arrow-path',
                'enviado' => 'heroicon-m-truck',
                'entregado' => 'heroicon-m-check-badge',
                'cancelado' => 'heroicon-m-x-circle',
               })
               ->sortable(),

               TextColumn::Make('metodo_pago')
               ->sortable()
               ->searchable(),

               TextColumn::make('estado_pago')
               ->sortable()
               ->badge()
               ->searchable(),

               TextColumn::make('created_at')
               ->label('Fecha de Pedido')
               ->dateTime()

            ])
            ->actions([
                Action::make('Ver Pedido')
                ->url(fn(Pedidos $record): string => PedidosResource::getUrl('view', ['record' => $record]))
                ->icon('heroicon-m-eye')
            ]);
    }
}
