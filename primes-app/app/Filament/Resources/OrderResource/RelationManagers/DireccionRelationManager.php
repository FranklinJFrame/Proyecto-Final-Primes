<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class DireccionRelationManager extends RelationManager
{
    protected static string $relationship = 'direccion'; // minúscula


    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                TextInput::make('apellido')
                    ->required()
                    ->maxLength(255),

                TextInput::make('telefono')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('ciudad')
                    ->required()
                    ->maxLength(255),

                TextInput::make('estado')
                    ->required()
                    ->maxLength(255),

                TextInput::make('codigo_postal')
                    ->required()
                    ->numeric()
                    ->maxLength(10),


                Textarea::make('direccion_calle')
                    ->required()
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('direccion_calle')
            ->columns([
                Tables\Columns\TextColumn::make('direccion_calle'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
