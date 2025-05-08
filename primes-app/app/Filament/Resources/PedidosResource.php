<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource\Pages;
use App\Filament\Resources\PedidosResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\DireccionRelationManager;
use App\Models\Pedidos;
use App\Models\PedidoProductos;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Colors;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;

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
                                    'stripe' => 'Stripe',
                                    'pce' => 'Pago contra entrega',  
                                ])
                                ->required(),

                            Select::make('estado_pago')
                                ->label('Estado del Pago')
                                ->options([
                                    'pendiente' => 'Pendiente',
                                    'pagado' => 'Pagado',
                                    'fallado' => 'Fallado'
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
                                    'DOP' => 'Peso Dominicano',
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

                        Section::make('Detalles del Pedido')->schema([
                            Repeater::make('productos')
                            ->relationship()
                            ->schema([

                                Select::make('producto_id')
                                    ->label('Producto')
                                    ->relationship('producto', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                                        $producto = Producto::find($state);
                                        if ($producto) {
                                            $set('precio_unitario', $producto->precio);
                                            $set('precio_total', $producto->precio);
                                        }
                                    }),
                         

                                TextInput::make('cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(3)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                        $precio_unitario = $get('precio_unitario');
                                        $set('precio_total', $state * $precio_unitario);
                                    }),


                                TextInput::make('precio_unitario')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->columnSpan(3)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                        $cantidad = $get('cantidad');
                                        $set('precio_total', $state * $cantidad);
                                    }),


                                TextInput::make('precio_total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->columnSpan(3)
                                    ->disabled(),


                            ])
                            ->columns(9)
                            ->columnSpanFull(),

                            Placeholder::make('total_general')
                            ->label('Total General')
                            ->visible(function (Get $get) {
                                $total = 0;
                                if ($repeaters = $get('productos')) {
                                    foreach ($repeaters as $key => $repeater) {
                                        $total += $get("productos.{$key}.precio_total") ?? 0;
                                    }
                                }
                                $total += $get('costo_envio') ?? 0;
                                return $total > 0;
                            })
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                
                                if (!$repeaters = $get('productos')) {
                                    return $total;
                                }
                                
                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("productos.{$key}.precio_total");
                                }

                                $costoEnvio = $get('costo_envio') ?? 0;
                                $total += $costoEnvio;

                                $moneda = $get('moneda') ?? 'DOP';
                                
                                return match($moneda) {
                                    'DOP' => 'RD$ ' . number_format($total, 2),
                                    'USD' => '$ ' . number_format($total, 2),
                                    'EUR' => '€ ' . number_format($total, 2),
                                    'MXN' => 'MX$ ' . number_format($total, 2),
                                    default => 'RD$ ' . number_format($total, 2),
                                };
                            })
                            ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_general')
                    ->label('Total')
                    ->state(function (Pedidos $record): float {
                        $subtotal = $record->productos->sum(function ($item) {
                            return $item->cantidad * $item->precio_unitario;
                        });
                        
                        return $subtotal + ($record->costo_envio ?? 0);
                    })
                    ->numeric()
                    ->sortable()
                    ->money(fn (Pedidos $record): string => $record->moneda ?? 'DOP')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Método de Pago')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado_pago')
                    ->label('Estado del Pago')
                    ->icon(fn (string $state): string => match ($state) {
                        'pendiente' => 'heroicon-o-clock',
                        'pagado' => 'heroicon-o-check-circle',
                        'fallado' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle'
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('moneda')
                    ->label('Moneda')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('pedido.costo_envio')
                    ->label('Costo de Envío')
                    ->money('DOP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('metodo_envio')
                    ->label('Método de Envío')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'nuevo' => 'info',
                        'procesando' => 'warning',
                        'enviado' => 'success',
                        'entregado' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'nuevo' => 'heroicon-o-shopping-cart',
                        'procesando' => 'heroicon-o-arrow-path',
                        'enviado' => 'heroicon-o-truck',
                        'entregado' => 'heroicon-o-check-circle',
                        'cancelado' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
        DireccionRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
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
