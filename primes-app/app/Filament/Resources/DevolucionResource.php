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
use Illuminate\Support\Facades\Mail;
use App\Mail\DevolucionEstadoMail;

class DevolucionResource extends Resource
{
    protected static ?string $model = Devolucion::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Devoluciones';
    protected static ?string $modelLabel = 'Devolución';
    protected static ?string $pluralModelLabel = 'Devoluciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Información del Cliente')
                            ->schema([
                                Placeholder::make('cliente_nombre_placeholder')
                                    ->label('Nombre del Cliente')
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
                                    ->label('Método de Pago')
                                    ->columnSpanFull(),
                            ])->columns(1)
                            ->description('Información del cliente que realizó la compra'),
                        
                        Section::make('Detalles de la Solicitud')
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
                        
                        Forms\Components\Section::make('Artículos a Devolver')
                            ->description('Lista de productos que el cliente desea devolver')
                            ->schema([
                                // Mostrar los productos de la devolución de forma no editable
                                ViewField::make('devolucionProductos.productos')
                                    ->label('')
                                    ->view('filament.fields.devolucion-productos-view')
                                    ->columnSpanFull(),
                            ]),

                    ])->columnSpan(['lg' => 2]),
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Estado de la Solicitud')
                            ->schema([
                                Select::make('estado')
                                    ->label('Estado')
                                    ->options([
                                        'pendiente' => '🕒 En Revisión',
                                        'aprobada' => '✅ Aprobada',
                                        'rechazada' => '❌ Rechazada',
                                        'recibido' => '🟢 Recibido',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Devolucion $record, callable $set) {
                                        $pedido = $record->pedido;
                                        if (!$pedido) return;

                                        $estadoOriginalPedido = $pedido->estado;

                                        // --- Actualización de estado del pedido según la devolución ---
                                        if ($state === 'aprobada') {
                                            $pedido->estado = Pedidos::ESTADO_REEMBOLSADO;
                                            $pedido->save();
                                        } elseif ($state === 'rechazada') {
                                            if ($estadoOriginalPedido === Pedidos::ESTADO_REEMBOLSADO) {
                                                $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
                                            } elseif ($estadoOriginalPedido === Pedidos::ESTADO_PROCESO_DEVOLUCION || $estadoOriginalPedido === 'entregado') {
                                                $pedido->estado = 'entregado';
                                            }
                                            $pedido->save();
                                        } elseif ($state === 'pendiente') {
                                            if ($estadoOriginalPedido === Pedidos::ESTADO_REEMBOLSADO) {
                                                $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
                                                $pedido->save();
                                            } elseif ($estadoOriginalPedido === 'entregado') {
                                                $pedido->estado = Pedidos::ESTADO_PROCESO_DEVOLUCION;
                                                $pedido->save();
                                            }
                                        } else if ($state === 'recibido') {
                                            // Estado personalizado: producto recibido físicamente
                                            // Aquí podrías agregar lógica extra si lo necesitas
                                        }

                                        // --- Notificación por correo al usuario y soporte ---
                                        $user = $record->user;
                                        $soporte = 'franklin1903rp@protonmail.com';

                                        // Limpiar admin_notes si el estado NO es 'rechazada' ni 'recibido'
                                        if (!in_array($state, ['rechazada', 'recibido'])) {
                                            $record->admin_notes = null;
                                            $record->save();
                                        }

                                        $adminMsg = '';
                                        if (in_array($state, ['rechazada', 'recibido']) && !empty($record->admin_notes)) {
                                            $adminMsg = "\n\nMensaje del administrador: {$record->admin_notes}";
                                        }

                                        if ($user && $user->email) {
                                            $titulo = '';
                                            $mensaje = '';
                                            if ($state === 'pendiente') {
                                                $titulo = 'Tu solicitud de devolución está en proceso de revisión';
                                                $mensaje = "Hemos recibido tu solicitud de devolución. Nuestro equipo la está revisando y te notificaremos cuando tengamos una actualización.";
                                            } elseif ($state === 'recibido') {
                                                $titulo = 'Tu producto ha sido recibido y está en proceso de revisión';
                                                $mensaje = "Tu producto ha sido recibido por nuestro equipo. Ahora estamos analizando el estado del producto y verificando si cumple con los requisitos para la aprobación de la devolución." . $adminMsg;
                                            } elseif ($state === 'aprobada') {
                                                $titulo = '¡Tu devolución ha sido aprobada!';
                                                $mensaje = "¡Tu devolución ha sido aprobada! Pronto recibirás instrucciones para el reembolso o cambio de producto.\n\nGracias por confiar en nosotros.";
                                            } elseif ($state === 'rechazada') {
                                                $titulo = 'Tu devolución no se ha podido completar';
                                                $mensaje = "Lamentablemente, tu devolución no ha sido aprobada." . $adminMsg . "\n\nSi tienes dudas, contáctanos respondiendo a este correo.";
                                            }
                                            if ($titulo && $mensaje) {
                                                // Notifica al usuario
                                                \Mail::to($user->email)->send(new \App\Mail\DevolucionEstadoMail($record, $titulo, $mensaje, $soporte));
                                                // Notifica a soporte si es nueva o rechazada
                                                if ($state === 'pendiente' || $state === 'rechazada') {
                                                    $tituloSoporte = $state === 'pendiente'
                                                        ? 'Nueva solicitud de devolución recibida'
                                                        : 'Devolución rechazada - requiere atención';
                                                    $mensajeSoporte = $state === 'pendiente'
                                                        ? "Se ha recibido una nueva solicitud de devolución de {$user->name} (ID: #" . str_pad($record->id, 6, '0', STR_PAD_LEFT) . ")."
                                                        : "La devolución de {$user->name} (ID: #" . str_pad($record->id, 6, '0', STR_PAD_LEFT) . ") ha sido rechazada. Motivo: {$record->admin_notes}";
                                                    \Mail::to($soporte)->send(new \App\Mail\DevolucionEstadoMail($record, $tituloSoporte, $mensajeSoporte, $user->email));
                                                }
                                            }
                                        }
                                    }),
                                Textarea::make('admin_notes')
                                    ->label('Observaciones')
                                    ->helperText('Notas internas sobre la evaluación de la devolución')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Solicitud #')
                    ->formatStateUsing(fn ($state) => str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pedido.id')
                    ->label('Pedido #')
                    ->formatStateUsing(fn ($state) => str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('motivo')
                    ->label('Motivo')
                    ->limit(50)
                    ->tooltip(fn (Devolucion $record): string => $record->motivo ?? 'N/A'),
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => '🕒 En Revisión',
                        'aprobada' => '✅ Aprobada',
                        'rechazada' => '❌ Rechazada',
                        'recibido' => '🟢 Recibido',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobada',
                        'danger' => 'rechazada',
                        'success' => 'recibido',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Filtrar por Estado')
                    ->options([
                        'pendiente' => '🕒 En Revisión',
                        'aprobada' => '✅ Aprobada',
                        'rechazada' => '❌ Rechazada',
                        'recibido' => '🟢 Recibido',
                    ])
                    ->multiple()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Podrías añadir una acción para ver el pedido asociado rápidamente
                Action::make('Ver Pedido')
                    ->label('Ver Pedido Original')
                    ->url(fn (Devolucion $record): string => filled($record->pedido_id) ? route('filament.admin.resources.pedidos.edit', $record->pedido_id) : '#')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('success')
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

    public static function getNavigationBadge(): ?string
    {
        // Solo devoluciones pendientes (no vistas)
        return (string) static::getModel()::where('estado', 'pendiente')->count();
    }
}
