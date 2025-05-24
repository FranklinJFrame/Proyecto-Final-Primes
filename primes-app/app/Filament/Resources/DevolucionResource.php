<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DevolucionResource\Pages;
// use App\Filament\Resources\DevolucionResource\RelationManagers; // Comentado
use App\Models\Devolucion;
use App\Models\Pedidos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload; // Para mostrar imágenes adjuntas
use Filament\Forms\Components\ViewField; // Para mostrar productos de la devolución
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\View; // Asegúrate de importar View
use App\Models\User; // Import User model
use App\Models\DatosTarj; // Import DatosTarj model
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Log; // <--- IMPORTANTE: Añadir esto

class DevolucionResource extends Resource
{
    protected static ?string $model = Devolucion::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down'; // Icono más apropiado
    protected static ?string $navigationGroup = 'Shop'; // Agrupar con otros recursos de tienda
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Información de Pago (Referencial)')
                            ->schema([
                                Placeholder::make('cliente_nombre_placeholder')
                                    ->label('Cliente')
                                    ->content(function (?Devolucion $record): string {
                                        if (!$record) {
                                            Log::info('DevolucionResource: Record es null para cliente.');
                                            return 'Record no cargado';
                                        }
                                        Log::info('DevolucionResource: Verificando cliente', [
                                            'devolucion_id' => $record->id,
                                            'pedido_id' => $record->pedido_id,
                                            'is_pedido_loaded' => $record->relationLoaded('pedido'),
                                            'pedido_exists' => !is_null($record->pedido),
                                            'pedido_user_id' => $record->pedido ? $record->pedido->user_id : 'pedido_null',
                                            'is_pedido_user_loaded' => $record->pedido ? $record->pedido->relationLoaded('user') : 'pedido_null',
                                            'pedido_user_exists' => $record->pedido && !is_null($record->pedido->user),
                                            'user_name' => ($record->pedido && $record->pedido->user) ? $record->pedido->user->name : 'no_user_name'
                                        ]);

                                        if ($record->relationLoaded('pedido') && $record->pedido &&
                                            $record->pedido->relationLoaded('user') && $record->pedido->user) {
                                            return e($record->pedido->user->name);
                                        }
                                        return 'Nombre no disponible (ver logs)';
                                    }),
                                View::make('filament.fields.display-card-info')
                                    ->label('Tarjeta Utilizada (Datos Guardados por el Cliente)')
                                    ->columnSpanFull(),
                            ])->columns(1)
                            ->description('Información referencial basada en datos guardados por el cliente.'),
                        
                        Section::make('Detalles de la Devolución')
                            ->schema([
                                Placeholder::make('pedido_id_display')
                                    ->label('ID Pedido')
                                    ->content(function (?Devolucion $record): string {
                                        if ($record && $record->pedido_id) {
                                            return str_pad($record->pedido_id, 6, '0', STR_PAD_LEFT);
                                        }
                                        return 'ID no disponible';
                                    }),
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Solicitante Devolución')
                                    ->disabled(),
                                Textarea::make('motivo')
                                    ->label('Motivo del Cliente')
                                    ->disabled()
                                    ->columnSpanFull(),

                                // Nueva forma de mostrar imágenes adjuntas
                                View::make('filament.fields.display-devolucion-images')
                                    ->label('Imágenes Adjuntas por el Cliente')
                                    ->columnSpanFull()
                                    // Pasamos los datos necesarios a la vista. 
                                    // El registro actual ($record) estará disponible automáticamente en la vista.
                                    ->visible(fn (Devolucion $record): bool => !empty($record->imagenes_adjuntas)),
                                
                                Placeholder::make('no_imagenes_placeholder')
                                    ->label('Imágenes Adjuntas por el Cliente')
                                    ->content('El cliente no adjuntó imágenes.')
                                    ->columnSpanFull()
                                    ->visible(fn (Devolucion $record): bool => empty($record->imagenes_adjuntas)),

                            ])->columns(2),
                        
                        Forms\Components\Section::make('Productos Devueltos')
                            ->description('Productos incluidos en esta solicitud de devolución.')
                            ->schema([
                                // Mostrar los productos de la devolución de forma no editable
                                ViewField::make('devolucionProductos.productos')
                                    ->label('')
                                    ->view('filament.fields.devolucion-productos-view') // Necesitarás crear esta vista blade
                                    ->columnSpanFull(),
                            ]),

                    ])->columnSpan(['lg' => 2]),
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Estado de la Devolución')
                            ->schema([
                                Select::make('estado')
                                    ->options([
                                        'pendiente' => 'Pendiente',
                                        'aprobada' => 'Aprobada',
                                        'rechazada' => 'Rechazada',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Devolucion $record, callable $set) {
                                        $pedido = $record->pedido;
                                        if (!$pedido) return;

                                        // Guardar el estado actual del pedido antes de cualquier cambio, si no es uno de los estados de devolución
                                        // Esto es una simplificación. Una solución más robusta podría tener un campo dedicado para el estado previo.
                                        $estadoOriginalPedido = $pedido->estado;

                                        if ($state === 'aprobada') {
                                            $pedido->estado = Pedidos::ESTADO_REEMBOLSADO;
                                            $pedido->save();
                                            // TODO: Disparar evento/notificación para procesar el reembolso real.
                                        } elseif ($state === 'rechazada') {
                                            if ($estadoOriginalPedido === Pedidos::ESTADO_REEMBOLSADO) {
                                                // Si previamente estaba reembolsado y ahora se rechaza, vuelve a proceso para revisión.
                                                $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
                                            } elseif ($estadoOriginalPedido === Pedidos::ESTADO_PROCESO_DEVOLUCION || $estadoOriginalPedido === 'entregado') {
                                                // Si estaba en proceso o entregado y se rechaza la solicitud,
                                                // el pedido debería volver a 'entregado' (asumiendo que esa es la meta final si no hay devolución)
                                                // o al estado anterior si tuviéramos forma de saberlo con certeza.
                                                // Por ahora, si la solicitud es rechazada y no estaba reembolsado, lo ponemos como 'entregado'.
                                                // Esto asume que 'entregado' es el estado base antes de una devolución.
                                                // Si el pedido estaba en 'proceso de devolucion' por esta solicitud, y se rechaza, no tiene sentido que siga en 'proceso de devolucion'
                                                $pedido->estado = 'entregado'; 
                                            }
                                            $pedido->save();
                                        } elseif ($state === 'pendiente') {
                                            // Si se vuelve a poner como pendiente desde aprobada/reembolsado
                                            if ($estadoOriginalPedido === Pedidos::ESTADO_REEMBOLSADO) {
                                                $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
                                                $pedido->save();
                                            }
                                            // Si estaba rechazada y vuelve a pendiente, y el pedido estaba 'entregado', podría volver a 'proceso de devolucion'.
                                            // Pero si ya está en 'proceso de devolucion', no hacer nada.
                                            elseif ($estadoOriginalPedido === 'entregado') {
                                                $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION; 
                                                $pedido->save();
                                            }
                                        }
                                    }),
                                Textarea::make('admin_notes')
                                    ->label('Notas del Administrador')
                                    ->helperText('Notas internas para esta devolución.')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('pedido.id')->label('Pedido ID')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Cliente')->sortable()->searchable(),
                TextColumn::make('motivo')->label('Motivo')->limit(50)->tooltip(fn (Devolucion $record): string => $record->motivo ?? 'N/A'),
                TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobada',
                        'danger' => 'rechazada',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')->label('Fecha Solicitud')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'rechazada' => 'Rechazada',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Podrías añadir una acción para ver el pedido asociado rápidamente
                Action::make('Ver Pedido')
                    ->url(fn (Devolucion $record): string => filled($record->pedido_id) ? route('filament.admin.resources.pedidos.edit', $record->pedido_id) : '#')
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->disabled(fn (Devolucion $record): bool => !filled($record->pedido_id)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(), // Quizás no quieras eliminar devoluciones masivamente
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\DevolucionProductosRelationManager::class, // Comentado
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevolucions::route('/'),
            // 'create' => Pages\CreateDevolucion::route('/create'), // Admin no crea devoluciones, solo gestiona
            'edit' => Pages\EditDevolucion::route('/{record}/edit'),
            'view' => Pages\ViewDevolucion::route('/{record}'), // Habilitar vista si es necesario
        ];
    }

    // Opcional: Para que al aprobar la devolución y cambiar el estado del pedido a 'reembolsado',
    // se muestre correctamente en el resource de Pedidos.
    public static function getEloquentQuery(): Builder
    {
        Log::info('DevolucionResource: getEloquentQuery ejecutado.'); // Log para saber que se llama
        // Aseguramos que se cargue el usuario del pedido y el usuario de la devolución.
        return parent::getEloquentQuery()->with([
            'pedido.user', // Para acceder a $record->pedido->user->name
            'user',        // Para acceder a $record->user->name (usuario que solicitó la devolución)
            'devolucionProductos.pedidoProducto.producto'
        ]);
    }
}
