<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                                    ->reactive() // Hacer que el campo sea reactivo
                                    ->debounce(500) // Esperar 500ms antes de ejecutar el evento
                                    ->afterStateUpdated(function ($state, $set) {
                                        $set('slug', \Illuminate\Support\Str::slug($state)); // Generar el slug automáticamente
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated(), // Asegúrate de que el valor se envíe al guardar

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
                                    ->label('Currency') // Etiqueta en inglés
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
                                        // Agrega más monedas según sea necesario
                                    ])
                                    ->searchable() // Permitir búsqueda en el select
                                    ->required(),
                            ])
                            ->columnSpan(1),

                        // Categorías y Marcas
                        Forms\Components\Section::make('Categorias y Marcas')
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
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categoria_id')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('marca_id')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('esta_activo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('es_destacado')
                    ->boolean(),

                Tables\Columns\IconColumn::make('en_stock')
                    ->boolean(),

                Tables\Columns\IconColumn::make('en_oferta')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
