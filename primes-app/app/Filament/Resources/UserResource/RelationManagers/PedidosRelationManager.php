<?php

namespace App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\PedidosResource;
use App\Models\Pedidos;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PedidosRelationManager extends RelationManager
{
    protected static string $relationship = 'pedidos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
               TextColumn::make('id')
               ->label('Pedido ID')
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
                'nuevo' => 'heroicon-o-sparkles',
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
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('Ver Pedido')
                ->url(fn (Pedidos $record):string => PedidosResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
        
                 Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
