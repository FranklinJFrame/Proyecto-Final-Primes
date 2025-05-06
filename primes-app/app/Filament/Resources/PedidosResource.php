<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource\Pages;
use App\Filament\Resources\PedidosResource\RelationManagers;
use App\Models\Pedidos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\Colors;
class PedidosResource extends Resource
{
    protected static ?string $model = Pedidos::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información del Pedido')
                        ->schema([
                            Select::make('user_id')
                                ->label('Cliente')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('metodo_pago')
                                ->label('Método de Pago')
                                ->options([
                                    'pendiente' => 'Pendiente',
                                    'pago' => 'Pagó',
                                    'fallo' => 'Falló',
                                ])
                                ->default('pendiente')
                                ->required(),

                            ToggleButtons::make('estado')
                                ->label('Estado del Pedido')
                                ->inline()
                                ->default('nuevo')
                                ->options([
                                    'nuevo' => 'Nuevo',
                                    'procesando' => 'Procesando',
                                    'enviado' => 'Enviado',
                                    'entregado' => 'Entregado',
                                    'cancelado' => 'Cancelado',
                                ])
                                ->colors([
                                    'nuevo' => 'info',
                                    'procesando' => 'warning',
                                    'enviado' => 'success',
                                    'entregado' => 'success',
                                    'cancelado' => 'danger',
                                ])
                                ->icons([
                                    'nuevo' => 'heroicon-o-shopping-cart',
                                    'procesando' => 'heroicon-o-arrow-path',
                                    'enviado' => 'heroicon-o-truck',
                                    'entregado' => 'heroicon-o-check-circle',
                                    'cancelado' => 'heroicon-o-x-circle',
                                ])
                                ->required(),

                            Select::make('moneda')
                                ->label('Moneda')
                                ->options([
                                    'USD' => 'Dólares',
                                    'EUR' => 'Euros',
                                    'MXN' => 'Pesos Mexicanos',
                                    'DOP' => 'Peso',
                                ])
                                ->default('USD')
                                ->required(),

                            Select::make('metodo_envio')
                                ->label('Método de Envío')
                                ->options([
                                    'fedex' => 'FedEx',
                                    'ups' => 'UPS',
                                    'dhl' => 'DHL',
                                    'usps' => 'USPS',
                                ])
                                ->required()
                                ->prefixIcon('heroicon-o-truck'),

                            Forms\Components\Textarea::make('notas')
                                ->label('Notas')
                                ->placeholder('Notas adicionales sobre el pedido')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        
                        ->columns(2)
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedidos::route('/create'),
            'view' => Pages\ViewPedidos::route('/{record}'),
            'edit' => Pages\EditPedidos::route('/{record}/edit'),
        ];
    }
}
