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
    protected static string $relationship = 'productosCompatibles';

    public function form(Form $form): Form
    {
        $producto = $this->getOwnerRecord();
        $categoria = $producto->categoria;

        if (!$categoria || !$categoria->is_compatible_device) {
            return $form->schema([
                Forms\Components\Placeholder::make('no_compatible')
                    ->content('Este producto no puede tener productos compatibles porque su categoría no es compatible.')
            ]);
        }

        $compatibleCategoryIds = \App\Models\CategoriasCompatible::where('categoria_id', $categoria->id)
            ->pluck('compatible_category_id')
            ->toArray();

        $productosCompatibles = Producto::whereIn('categoria_id', $compatibleCategoryIds)
            ->where('id', '!=', $producto->id)
            ->pluck('nombre', 'id')
            ->toArray();

        return $form
            ->schema([
                Forms\Components\Select::make('productos_compatibles')
                    ->label('Productos compatibles')
                    ->options($productosCompatibles)
                    ->multiple()
                    ->helperText('Selecciona los productos compatibles y presiona guardar para agregarlos automáticamente al final de la descripción.'),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->action(function (array $data, RelationManager $livewire) {
                        $producto = $livewire->getOwnerRecord();
                        $categoria = $producto->categoria;

                        if ($categoria && $categoria->is_compatible_device && !empty($data['productos_compatibles'])) {
                            $nombres = \App\Models\Producto::whereIn('id', $data['productos_compatibles'])->pluck('nombre')->toArray();

                            // Obtiene la descripción actual del formulario principal
                            $parent = $livewire->getOwnerRecord();
                            $form = $livewire->getForm();
                            $descripcionActual = $form->getState()['descripcion'] ?? '';

                            // Elimina sección anterior de productos compatibles si existe
                            $descripcion = trim($descripcionActual);
                            $descripcion = preg_replace('/^productos compatibles:(\n.+)*/m', '', $descripcion);

                            // Agrega la nueva sección al final
                            $linea = "productos compatibles:\n" . implode("\n", $nombres);
                            $descripcion = trim($descripcion . "\n" . $linea);

                            // Actualiza el campo descripcion en el formulario principal (NO en la BD)
                            $form->fill(['descripcion' => $descripcion]);
                        }
                    })
                    ->form([
                        Forms\Components\Select::make('productos_compatibles')
                            ->label('Productos compatibles')
                            ->options(function (RelationManager $livewire) {
                                $producto = $livewire->getOwnerRecord();
                                $categoria = $producto->categoria;
                                if (!$categoria) return [];
                                $compatibleCategoryIds = \App\Models\CategoriasCompatible::where('categoria_id', $categoria->id)
                                    ->pluck('compatible_category_id')
                                    ->toArray();
                                return Producto::whereIn('categoria_id', $compatibleCategoryIds)
                                    ->where('id', '!=', $producto->id)
                                    ->pluck('nombre', 'id')
                                    ->toArray();
                            })
                            ->multiple()
                            ->required(),
                    ]),
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