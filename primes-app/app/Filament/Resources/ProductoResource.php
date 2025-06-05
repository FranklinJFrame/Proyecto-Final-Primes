<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\RelationManagers\ProductosCompatiblesRelationManager;
use App\Filament\Resources\ProductoResource\Pages;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?int $navigationSort = 4;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // Panel Principal - Información Básica
                        Forms\Components\Section::make('Información Principal')
                            ->description('Información básica del producto')
                            ->icon('heroicon-o-information-circle')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre del Producto')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $set('slug', \Illuminate\Support\Str::slug($state ?? ''));
                                    })
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique('productos', 'slug', ignoreRecord: true)
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\MarkdownEditor::make('descripcion')
                                    ->label('Descripción')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                        'undo',
                                        'redo',
                                    ])
                                    ->columnSpanFull(),

                                // --- Productos Compatibles: Vertical y con botón ---
                                Forms\Components\Fieldset::make('Productos Compatibles')
                                    ->schema([
                                        Forms\Components\Select::make('productos_compatibles_temp')
                                            ->label('Selecciona productos compatibles')
                                            ->options(function ($get) {
                                                $categoriaId = $get('categoria_id');
                                                if (!$categoriaId) {
                                                    return [];
                                                }
                                                $compatibleCategoryIds = \App\Models\CategoriasCompatible::where('categoria_id', $categoriaId)
                                                    ->pluck('compatible_category_id')
                                                    ->toArray();
                                                return \App\Models\Producto::whereIn('categoria_id', $compatibleCategoryIds)
                                                    ->pluck('nombre', 'id')
                                                    ->toArray();
                                            })
                                            ->multiple()
                                            ->searchable()
                                            ->columnSpanFull()
                                            ->helperText('Selecciona uno o varios productos compatibles.'),

                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('agregar_a_descripcion')
                                                ->label('Agregar a descripción')
                                                ->color('primary')
                                                ->action(function ($get, $set) {
                                                    $productosIds = $get('productos_compatibles_temp') ?? [];
                                                    $nombres = \App\Models\Producto::whereIn('id', $productosIds)->pluck('nombre')->toArray();
                                                    if (count($nombres)) {
                                                        $descripcion = $get('descripcion') ?? '';
                                                        // Elimina sección anterior de productos compatibles si existe
                                                        $descripcion = preg_replace('/productos compatibles:(\n.+)*/i', '', $descripcion);
                                                        // Agrega la nueva sección en el formato solicitado
                                                        $linea = "productos compatibles:\n" . implode("\n", $nombres);
                                                        $descripcion = trim($descripcion . "\n" . $linea);
                                                        $set('descripcion', $descripcion);
                                                    }
                                                }),
                                        ])->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),
                                // --- Fin productos compatibles ---
                            ])
                            ->columns(['md' => 4])
                            ->columnSpan(['lg' => 2]),

                        // Panel Lateral - Detalles y Estado
                        Forms\Components\Grid::make()
                            ->schema([
                                // Sección de Precios
                                Forms\Components\Section::make('Precios y Stock')
                                    ->description('Gestión de precios y disponibilidad')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->schema([
                                        Forms\Components\TextInput::make('precio')
                                            ->label('Precio')
                                            ->numeric()
                                            ->required()
                                            ->prefix('$')
                                            ->suffixIcon('heroicon-m-currency-dollar'),

                                        Forms\Components\Select::make('moneda')
                                            ->label('Moneda')
                                            ->options([
                                                'DOP' => 'Peso Dominicano (DOP)',
                                                'USD' => 'Dólar Americano (USD)',
                                                'EUR' => 'Euro (EUR)',
                                            ])
                                            ->searchable()
                                            ->required()
                                            ->default('DOP'),

                                        Forms\Components\TextInput::make('cantidad')
                                            ->label('Cantidad en Stock')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->minValue(0)
                                            ->step(1)
                                            ->suffixIcon('heroicon-m-cube'),
                                    ])
                                    ->columns(1),

                                // Sección de Categorización
                                Forms\Components\Section::make('Categorización')
                                    ->description('Asignar marca y categoría')
                                    ->icon('heroicon-o-tag')
                                    ->schema([
                                        Forms\Components\Select::make('categoria_id')
                                            ->label('Categoría')
                                            ->relationship('categoria', 'nombre')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('nombre')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\Toggle::make('esta_activo')
                                                    ->label('¿Activa?')
                                                    ->default(true),
                                            ]),

                                        Forms\Components\Select::make('marca_id')
                                            ->label('Marca')
                                            ->relationship('marca', 'nombre')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('nombre')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\Toggle::make('esta_activo')
                                                    ->label('¿Activa?')
                                                    ->default(true),
                                            ]),
                                    ])
                                    ->columns(1),

                                // Sección de Estado
                                Forms\Components\Section::make('Estado del Producto')
                                    ->description('Configuración de visibilidad y características')
                                    ->icon('heroicon-o-cog')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Toggle::make('esta_activo')
                                                    ->label('Producto Activo')
                                                    ->helperText('Mostrar en la tienda')
                                                    ->default(true),

                                                Forms\Components\Toggle::make('en_stock')
                                                    ->label('En Stock')
                                                    ->helperText('Disponible para compra')
                                                    ->default(true),

                                                Forms\Components\Toggle::make('es_destacado')
                                                    ->label('Destacado')
                                                    ->helperText('Mostrar en sección destacada'),

                                                Forms\Components\Toggle::make('en_oferta')
                                                    ->label('En Oferta')
                                                    ->helperText('Aplicar descuento'),
                                                    
                                                Forms\Components\Toggle::make('es_devolucible')
                                                    ->label('Devoluciones Permitidas')
                                                    ->helperText('Permite devolución')
                                                    ->default(true),
                                            ])
                                            ->columns(2),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),

                        // Sección de Imágenes
                        Forms\Components\Section::make('Galería de Imágenes')
                            ->description('Gestiona las imágenes del producto')
                            ->icon('heroicon-o-photo')
                            ->collapsible()
                            ->schema([
                                Forms\Components\FileUpload::make('imagenes')
                                    ->label('Imágenes')
                                    ->multiple()
                                    ->directory('products')
                                    ->maxFiles(5)
                                    ->reorderable()
                                    ->imageEditor()
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
                Tables\Columns\ImageColumn::make('imagenes')
                    ->label('Imagen')
                    ->disk('public')
                    ->size(80)
                    ->circular(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Producto $record): string => $record->descripcion ? \Illuminate\Support\Str::limit($record->descripcion, 50) : '')
                    ->wrap(),

                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('precio')
                    ->label('Precio')
                    ->money('DOP')
                    ->sortable()
                    ->alignment('right'),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Stock')
                    ->sortable()
                    ->alignment('center')
                    ->badge()
                    ->color(fn (Producto $record): string => 
                        $record->cantidad <= 5
                            ? 'danger'
                            : ($record->cantidad <= 10 
                                ? 'warning' 
                                : 'success'))
                    ->description(fn (Producto $record): string => 
                        $record->cantidad <= 5
                            ? '¡Stock Bajo!'
                            : ($record->cantidad <= 10 
                                ? 'Stock Limitado' 
                                : 'Stock Disponible')),

                Tables\Columns\IconColumn::make('esta_activo')
                    ->label('Activo')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('es_destacado')
                    ->label('Destacado')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('en_oferta')
                    ->label('Oferta')
                    ->boolean()
                    ->alignCenter(),

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
                Tables\Filters\SelectFilter::make('categoria')
                    ->relationship('categoria', 'nombre')
                    ->preload()
                    ->multiple()
                    ->label('Categoría'),

                Tables\Filters\SelectFilter::make('marca')
                    ->relationship('marca', 'nombre')
                    ->preload()
                    ->multiple()
                    ->label('Marca'),

                Tables\Filters\TernaryFilter::make('esta_activo')
                    ->label('Activo')
                    ->boolean()
                    ->trueLabel('Productos Activos')
                    ->falseLabel('Productos Inactivos')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('en_stock')
                    ->label('Stock')
                    ->boolean()
                    ->trueLabel('En Stock')
                    ->falseLabel('Sin Stock')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->link()
                ->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductosCompatiblesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
            'low-stock' => Pages\LowStockProductos::route('/low-stock'),
        ];
    }
}