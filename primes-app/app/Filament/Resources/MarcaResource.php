<?php

namespace App\Filament\Resources;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\MarcaResource\Pages;
use App\Models\Marca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class MarcaResource extends Resource
{
    protected static ?string $model = Marca::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // Panel Principal
                        Forms\Components\Section::make('Información de la Marca')
                            ->description('Datos principales de la marca')
                            ->icon('heroicon-o-building-storefront')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                        $set('slug', \Illuminate\Support\Str::slug($state));
                                    })
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Amigable')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Marca::class, 'slug', ignoreRecord: true)
                                    ->columnSpan(['md' => 2]),

                                Forms\Components\Toggle::make('esta_activa')
                                    ->label('Marca Activa')
                                    ->helperText('Mostrar en la tienda')
                                    ->default(true)
                                    ->columnSpan(['md' => 4]),
                            ])
                            ->columns(['md' => 4])
                            ->columnSpan(['lg' => 2]),

                        // Panel Lateral - Imagen
                        Forms\Components\Section::make('Imagen de la Marca')
                            ->description('Sube el logotipo o imagen representativa')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('imagen')
                                    ->label('Logotipo')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('marcas')
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
                    ->label('Logo')
                    ->disk('public')
                    ->size(80)
                    ->circular(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Marca $record): string => $record->slug)
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('esta_activa')
                    ->label('Activa')
                    ->boolean()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('productos_count')
                    ->label('Productos')
                    ->counts('productos')
                    ->sortable()
                    ->alignCenter()
                    ->color('primary'),

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
                    ->trueLabel('Marcas Activas')
                    ->falseLabel('Marcas Inactivas')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar esta marca? Esta acción no se puede deshacer.'),
                ])
                ->link()
                ->label('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('¿Estás seguro de que deseas eliminar las marcas seleccionadas? Esta acción no se puede deshacer.'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Marca'),
            ])
            ->emptyStateDescription('No hay marcas creadas aún. ¡Comienza creando una!');
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
            'index' => Pages\ListMarcas::route('/'),
            'create' => Pages\CreateMarca::route('/create'),
            'edit' => Pages\EditMarca::route('/{record}/edit'),
        ];
    }
}
