<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoriaResource\RelationManagers\CategoriasCompatiblesRelationManager;
use App\Filament\Resources\CategoriaResource\Pages;
use App\Models\Categoria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;

class CategoriaResource extends Resource
{
    protected static ?string $model = Categoria::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // Panel Principal
                        Forms\Components\Section::make('Información de la Categoría')
                            ->description('Datos principales de la categoría')
                            ->icon('heroicon-o-tag')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        $set('slug', \Illuminate\Support\Str::slug($state ?? ''));
                                    })
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique('categorias', 'slug', ignoreRecord: true)
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Toggle::make('esta_activa')
                                            ->label('Categoría Activa')
                                            ->helperText('Mostrar en la tienda')
                                            ->default(true),

                                        Forms\Components\Toggle::make('is_compatible_device')
                                            ->label('Dispositivo Compatible')
                                            ->helperText('¿Es un dispositivo que puede tener compatibilidades?')
                                            ->default(true),
                                    ])
                                    ->columns(2)
                                    ->columnSpan(['md' => 4]),
                            ])
                            ->columns(['md' => 4])
                            ->columnSpan(['lg' => 2]),

                        // Panel Lateral - Imagen
                        Forms\Components\Section::make('Imagen de la Categoría')
                            ->description('Sube una imagen representativa')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('imagen')
                                    ->label('Imagen')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('cloudinary')
                                    ->directory('categorias')
                                    ->imagePreviewHeight('250')
                                    ->maxSize(5120)
                                    ->helperText('Formato: JPG, PNG. Máximo 5MB.')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(['lg' => 3]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->disk('cloudinary')
                    ->directory('categorias')
                    ->size(80)
                    ->circular(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Categoria $record): string => $record->slug),

                Tables\Columns\IconColumn::make('esta_activa')
                    ->label('Activa')
                    ->boolean()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_compatible_device')
                    ->label('Compatible')
                    ->boolean()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-puzzle-piece')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('info')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('productos_count')
                    ->label('Productos')
                    ->counts('productos')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('esta_activa')
                    ->label('Estado')
                    ->boolean()
                    ->trueLabel('Categorías Activas')
                    ->falseLabel('Categorías Inactivas')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_compatible_device')
                    ->label('Compatibilidad')
                    ->boolean()
                    ->trueLabel('Dispositivos Compatibles')
                    ->falseLabel('No Compatibles')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.')
                        ->before(function ($record) {
                            if ($record->productos()->count() > 0) {
                                throw new \Filament\Notifications\Notification(
                                    'No puedes eliminar una categoría que tiene productos asignados. Elimina o reasigna los productos primero.'
                                );
                            }
                        }),
                ])
                ->link()
                ->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar las categorías seleccionadas? Esta acción no se puede deshacer.'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Categoría'),
            ])
            ->emptyStateDescription('No hay categorías creadas aún. ¡Comienza creando una!');
    }

    public static function getRelations(): array
    {
        return [
            CategoriasCompatiblesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategorias::route('/'),
            'create' => Pages\CreateCategoria::route('/create'),
            'edit' => Pages\EditCategoria::route('/{record}/edit'),
        ];
    }
}


