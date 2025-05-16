<?php

namespace App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductosCompatiblesRelationManager extends RelationManager
{
    protected static string $relationship = 'productosCompatibles'; // Usa el nombre exacto de la relación en tu modelo Producto

    

public function form(Form $form): Form
{
    $producto = $this->getOwnerRecord();
    $categoriaId = $producto->categoria_id;

    // IDs de categorías compatibles con la categoría de este producto
    $compatibleCategoryIds = \App\Models\CategoriasCompatible::where('categoria_id', $categoriaId)
        ->pluck('compatible_category_id')
        ->toArray();

    return $form
        ->schema([
            Forms\Components\Select::make('compatible_with_id')
                ->label('Producto compatible')
                ->relationship(
                    name: 'productoCompatible', // relación belongsTo en tu modelo pivote
                    titleAttribute: 'nombre',
                    modifyQueryUsing: fn ($query) => $query
                        ->whereIn('categoria_id', $compatibleCategoryIds)
                        ->where('id', '!=', $producto->id) // Opcional: no permitir el mismo producto
                )
                ->required(),
        ]);
}

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('compatible_with_id')
                    ->label('Producto Compatible')
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Producto::find($state)?->nombre;
                    })
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
                        $data['producto_id'] = $livewire->getOwnerRecord()->id;
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