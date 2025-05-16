<?php

namespace App\Filament\Resources\CategoriaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriasCompatiblesRelationManager extends RelationManager
{
    protected static string $relationship = 'categoriasCompatibles';

    public function form(Form $form): Form
    {

        $ownerId = $this->getOwnerRecord()->id;
    $alreadyRelatedIds = \App\Models\CategoriasCompatible::where('categoria_id', $ownerId)
        ->pluck('compatible_category_id')
        ->toArray();
        
        return $form
            ->schema([
                Forms\Components\Select::make('compatible_category_id')
                ->label('Categoría compatible')
                ->relationship(
                    name: 'categoriaCompatible',
                    titleAttribute: 'nombre',
                    modifyQueryUsing: fn ($query) => $query
                        ->where('is_compatible_device', 1)
                        ->where('id', '!=', $ownerId) // No permitir la misma categoría
                        ->whereNotIn('id', $alreadyRelatedIds) // No permitir ya relacionadas
                )
                ->required(),
            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('compatible_category_id')
        ->label('Categoría Compatible')
        ->formatStateUsing(function ($state) {
            // $state is compatible_category_id for this row
            return \App\Models\Categoria::find($state)?->nombre;
        })
        ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
            // Set categoria_id to the current page's category ID
            $data['categoria_id'] = $livewire->getOwnerRecord()->id;
            return $data;
        }),
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
