<?php

namespace App\Filament\Widgets;

use App\Models\Producto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class LowStockProductos extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Producto::query()->where('cantidad', '<=', 5)->orderBy('cantidad', 'asc')
            )
            ->columns([
                ImageColumn::make('imagenes.0')
                    ->label('Imagen')
                    ->disk('public')
                    ->size(40),
                TextColumn::make('nombre')
                    ->label('Producto')
                    ->searchable()
                    ->limit(30)
                    ->wrap()
                    ->grow(false), // Hace que no ocupe todo el ancho
                TextColumn::make('cantidad')->label('Stock')->sortable(),
                TextColumn::make('categoria.nombre')->label('Categoría')->sortable(),
                TextColumn::make('marca.nombre')->label('Marca')->sortable(),
            ]);
    }
}