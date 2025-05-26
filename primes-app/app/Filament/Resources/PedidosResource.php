<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource\Pages;
use App\Filament\Resources\PedidosResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\DireccionRelationManager;
use App\Models\Pedidos;
use App\Models\PedidoProductos;
use App\Models\Producto;
use App\Models\MetodosPago;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TextInputFilter;
use App\Models\Pagos;
use Filament\Resources\Pagos as FilamentPagosResource;
use App\Models\DatosTarj;


class PedidosResource extends Resource
{
    protected static ?string $model = Pedidos::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // Panel Principal - Información del Pedido
                        Forms\Components\Section::make('Información del Pedido')
                            ->description('Datos principales del pedido')
                            ->icon('heroicon-o-shopping-bag')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Cliente')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\Select::make('metodo_pago')
                                    ->label('Método de Pago')
                                    ->options(function() {
                                        return MetodosPago::where('esta_activo', true)
                                            ->pluck('nombre', 'id')
                                            ->toArray();
                                    })
                                    ->required()
                                    ->prefixIcon('heroicon-o-credit-card')
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\Select::make('estado_pago')
                                    ->label('Estado del Pago')
                                    ->options([
                                        'pendiente' => 'Pendiente',
                                        'pagado' => 'Pagado',
                                        'fallado' => 'Fallado'
                                    ])
                                    ->default('pendiente')
                                    ->required()
                                    ->prefixIcon('heroicon-o-banknotes')
                                    ->columnSpan(['md' => 2])
                                    ->afterStateUpdated(function ($state, $record) {
                                        if ($record && $record->pago) {
                                            $record->pago->update(['estado' => $state]);
                                        }
                                    }),

                                Forms\Components\Select::make('moneda')
                                    ->label('Moneda')
                                    ->options([
                                        'DOP' => 'Peso Dominicano (DOP)',
                                        'USD' => 'Dólar Americano (USD)',
                                        'EUR' => 'Euro (EUR)',
                                    ])
                                    ->default('DOP')
                                    ->required()
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\Select::make('metodo_envio')
                                    ->label('Método de Envío')
                                    ->options([
                                        'tecnobox_transport' => 'Tecnobox Transport',
                                        'caribes_tour' => 'Caribes Tour',
                                    ])
                                    ->required()
                                    ->prefixIcon('heroicon-o-truck')
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\ToggleButtons::make('estado')
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
                                    ->required()
                                    ->columnSpan(['md' => 4]),

                                Forms\Components\Textarea::make('notas')
                                    ->label('Notas')
                                    ->placeholder('Notas adicionales sobre el pedido')
                                    ->rows(3)
                                    ->columnSpan(['md' => 4]),
                            ])
                            ->columns(['md' => 4])
                            ->columnSpan(['lg' => 2]),

                        // Panel Lateral - Resumen
                        Forms\Components\Section::make('Resumen del Pedido')
                            ->description('Resumen y totales')
                            ->icon('heroicon-o-calculator')
                            ->schema([
                                Forms\Components\Placeholder::make('total_general')
                                    ->label('Total General')
                                    ->content(function (Get $get, Set $set) {
                                        $total = 0;
                                        
                                        if ($repeaters = $get('productos')) {
                                            foreach ($repeaters as $key => $repeater) {
                                                $total += $get("productos.{$key}.precio_total") ?? 0;
                                            }
                                        }

                                        $costoEnvio = $get('costo_envio') ?? 0;
                                        $total += $costoEnvio;

                                        $set('total_general', $total);

                                        $moneda = $get('moneda') ?? 'DOP';
                                        
                                        return match($moneda) {
                                            'DOP' => 'RD$ ' . number_format($total, 2),
                                            'USD' => '$ ' . number_format($total, 2),
                                            'EUR' => '€ ' . number_format($total, 2),
                                            default => 'RD$ ' . number_format($total, 2),
                                        };
                                    })
                                    ->columnSpanFull(),

                                Forms\Components\Hidden::make('total_general'),
                            ])
                            ->columnSpan(['lg' => 1]),

                        // Sección de Productos
                        Forms\Components\Section::make('Productos del Pedido')
                            ->description('Gestiona los productos del pedido')
                            ->icon('heroicon-o-gift')
                            ->schema([
                                Forms\Components\Repeater::make('productos')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Select::make('producto_id')
                                                    ->label('Producto')
                                                    ->relationship('producto', 'nombre')
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Set $set) {
                                                        $producto = Producto::find($state);
                                                        if ($producto) {
                                                            $set('precio_unitario', $producto->precio);
                                                            $set('precio_total', $producto->precio);
                                                        }
                                                    })
                                                    ->columnSpan(['sm' => 2]),

                                                Forms\Components\TextInput::make('cantidad')
                                                    ->label('Cantidad')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $precio_unitario = $get('precio_unitario');
                                                        $set('precio_total', $state * $precio_unitario);
                                                    })
                                                    ->suffixIcon('heroicon-m-cube'),

                                                Forms\Components\TextInput::make('precio_unitario')
                                                    ->label('Precio Unitario')
                                                    ->numeric()
                                                    ->prefix('RD$')
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $cantidad = $get('cantidad');
                                                        $set('precio_total', $state * $cantidad);
                                                    }),

                                                Forms\Components\TextInput::make('precio_total')
                                                    ->label('Total')
                                                    ->numeric()
                                                    ->prefix('RD$')
                                                    ->required()
                                                    ->disabled(),
                                            ])
                                            ->columns(['sm' => 5]),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => 
                                        $state['producto_id'] 
                                            ? Producto::find($state['producto_id'])?->nombre 
                                            : null
                                    )
                                    ->collapsible()
                                    ->defaultItems(1)
                                    ->reorderable(false)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan('full'),
                    ])
                    ->columns(['lg' => 3]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pedido')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

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
                    }),

                Tables\Columns\TextColumn::make('estado_pago')
                    ->label('Pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'pagado' => 'success',
                        'fallado' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pendiente' => 'heroicon-o-clock',
                        'pagado' => 'heroicon-o-check-circle',
                        'fallado' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('total_general')
                    ->label('Total')
                    ->money('DOP')
                    ->sortable()
                    ->alignRight()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Método de Pago')
                    ->badge()
                    ->formatStateUsing(function ($state, $record) {
                        // Si el método de pago es un id, busca el nombre
                        if (is_numeric($state)) {
                            $metodo = \App\Models\MetodosPago::find($state);
                            return $metodo ? ucfirst($metodo->nombre) : $state;
                        }
                        // Si es string, muestra el string
                        return ucfirst($state);
                    }),

                Tables\Columns\TextColumn::make('metodo_envio')
                    ->label('Envío')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->multiple()
                    ->options([
                        'nuevo' => 'Nuevo',
                        'procesando' => 'Procesando',
                        'enviado' => 'Enviado',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    ]),

                Tables\Filters\SelectFilter::make('estado_pago')
                    ->multiple()
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado' => 'Pagado',
                        'fallado' => 'Fallado',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                Tables\Filters\Filter::make('id')
                    ->form([
                        Forms\Components\TextInput::make('id')->label('ID Pedido'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['id'])) {
                            $query->where('id', $data['id']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Action::make('ver_factura')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('factura.pdf.get', $record->id))
                    ->openUrlInNewTab()
                    ->color('primary'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar este pedido? Esta acción no se puede deshacer.'),
                ])
                ->link()
                ->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar los pedidos seleccionados? Esta acción no se puede deshacer.'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Pedido'),
            ])
            ->emptyStateDescription('No hay pedidos registrados aún. ¡Comienza creando uno!');
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
