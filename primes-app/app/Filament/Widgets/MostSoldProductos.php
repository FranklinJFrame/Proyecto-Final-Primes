<?php


namespace App\Filament\Widgets;

use App\Models\Producto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\DB;

class MostSoldProductos extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $productosVendidos = Producto::query()
            ->leftJoin('pedido_productos as t2', 'productos.id', '=', 't2.producto_id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.cantidad',
                'productos.categoria_id',
                'productos.marca_id',
                'productos.precio',
                'productos.imagenes',
                DB::raw('COALESCE(SUM(t2.cantidad), 0) as cantidad_vendida')
            )
            ->groupBy(
                'productos.id',
                'productos.nombre',
                'productos.cantidad',
                'productos.categoria_id',
                'productos.marca_id',
                'productos.precio',
                'productos.imagenes'
            )
            ->orderByDesc('cantidad_vendida')
            ->limit(10);

        return $table
            ->query($productosVendidos)
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
                    ->grow(false),
                TextColumn::make('cantidad_vendida')
                    ->label('Vendidos')
                    ->sortable(),
                TextColumn::make('cantidad')
                    ->label('Stock')
                    ->sortable(),
                TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->sortable(),
                TextColumn::make('precio')
                    ->label('Precio')
                    ->money('DOP')
                    ->sortable(),
            ]);
    }
}