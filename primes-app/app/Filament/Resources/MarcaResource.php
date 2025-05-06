<?php

namespace App\Filament\Resources;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->debounce(500)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->unique(Marca::class, 'slug', ignoreRecord: true),

                Forms\Components\FileUpload::make('imagen')
                    ->image()
                    ->directory('marcas') // Directorio donde se guardarán las imágenes
                    ->imagePreviewHeight('250') // Aumentar la altura de la vista previa de la imagen
                    ->label('Imagen de la Marca'),

                Forms\Components\Toggle::make('esta_activa')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->extraAttributes(['class' => 'text-lg']), // Aumentar el tamaño del texto

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->extraAttributes(['class' => 'text-lg']), // Aumentar el tamaño del texto

                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->disk('public') // Especifica el disco donde se almacenan las imágenes
                    ->size(100), // Aumentar el tamaño de la imagen en la tabla

                Tables\Columns\IconColumn::make('esta_activa')
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
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
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
            'index' => Pages\ListMarcas::route('/'),
            'create' => Pages\CreateMarca::route('/create'),
            'edit' => Pages\EditMarca::route('/{record}/edit'),
        ];
    }
}
