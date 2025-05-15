<?php

namespace App\Filament\Resources;

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
                Forms\Components\Group::make()
                    ->schema([
                        // Información del Producto
                        Forms\Components\Section::make('Información del Producto')
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->debounce(500)
                                    ->afterStateUpdated(function ($state, $set) {
                                        $set('slug', \Illuminate\Support\Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\MarkdownEditor::make('descripcion')
                                    ->label('Description')
                                    ->columnSpanFull()
                                    ->fileAttachmentsDirectory('products'),
                            ])
                            ->columns(2),

                        // Imágenes
                        Forms\Components\Section::make('Imágenes')
                            ->schema([
                                Forms\Components\FileUpload::make('imagenes')
                                    ->label('Images')
                                    ->multiple()
                                    ->directory('products')
                                    ->maxFiles(5)
                                    ->reorderable(),
                            ])
                            ->columnSpan(2),

                        // Precio
                        Forms\Components\Section::make('Precio')
                            ->schema([
                                Forms\Components\TextInput::make('precio')
                                    ->label('Price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$'),

                                Forms\Components\Select::make('moneda')
                                    ->label('Tipo de Moneda')
                                    ->options([
                                        'USD' => 'US Dollar',
                                        'EUR' => 'Euro',
                                        'JPY' => 'Japanese Yen',
                                        'GBP' => 'British Pound',
                                        'AUD' => 'Australian Dollar',
                                        'CAD' => 'Canadian Dollar',
                                        'CHF' => 'Swiss Franc',
                                        'CNY' => 'Chinese Yuan',
                                        'SEK' => 'Swedish Krona',
                                        'NZD' => 'New Zealand Dollar',
                                        'DOP' => 'Dominican Peso',
                                    ])
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columnSpan(2),

                        // Categorías y Marcas
                        Forms\Components\Section::make('Marcas y Categorías')
                            ->schema([
                                Forms\Components\Select::make('categoria_id')
                                    ->label('Categoria')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('categoria', 'nombre'),

                                Forms\Components\Select::make('marca_id')
                                    ->label('Marca')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('marca', 'nombre'),
                            ])
                            ->columnSpan(2),

                        // Estado
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\TextInput::make('cantidad')
                                    ->label('Cantidad en Stock')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->minValue(0)
                                    ->step(1),

                                Forms\Components\Toggle::make('en_stock')
                                    ->label('en_stock')
                                    ->required()
                                    ->default(true),

                                Forms\Components\Toggle::make('esta_activo')
                                    ->label('esta_activo')
                                    ->required()
                                    ->default(true),

                                Forms\Components\Toggle::make('es_destacado')
                                    ->label('es_destacado')
                                    ->default(false),

                                Forms\Components\Toggle::make('en_oferta')
                                    ->label('en_oferta')
                                    ->default(false),
                                    
                                Forms\Components\Toggle::make('es_devolucible')
                                    ->label('Es devolucible')
                                    ->helperText('Indica si el producto puede ser devuelto después de la compra')
                                    ->default(true),
                            ])
                            ->columnSpan(2),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagenes')
                    ->label('Images') // Etiqueta para la columna
                    ->disk('public') // Especifica el disco donde se almacenan las imágenes
                    ->size(80), // Tamaño de las imágenes en la tabla

                Tables\Columns\TextColumn::make('categoria_id')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('marca_id')
                    ->label('Brand')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),

                Tables\Columns\TextColumn::make('precio')
                    ->label('Price')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('esta_activo')
                    ->label('Is Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('es_destacado')
                    ->label('Es Destacado')
                    ->boolean(),

                Tables\Columns\IconColumn::make('en_stock')
                    ->label('En Stock')
                    ->boolean(),

                Tables\Columns\IconColumn::make('en_oferta')
                    ->label('En oferta')
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('es_devolucible')
                    ->label('Es Devolucible')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn ( $record): string => 
                        $record->cantidad <= 5
                            ? 'danger'
                            : ($record->cantidad <= 10 
                                ? 'warning' 
                                : 'success'))
                    ->description(fn ( $record): string => 
                        $record->cantidad <= 5
                            ? '¡Stock Bajo!'
                            : ($record->cantidad <= 10 
                                ? 'Stock Limitado' 
                                : 'Stock Disponible')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}