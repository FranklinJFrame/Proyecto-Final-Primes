<?php

namespace App\Filament\Widgets;

use App\Models\Producto;
use Filament\Notifications\Notification;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class LowStockProductos extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function mount(): void
    {
        $count = Producto::where('cantidad', '<=', 5)->count();
        if ($count > 0) {
            Notification::make()
                ->title('¡Atención!')
                ->body("Hay {$count} producto(s) con stock menor o igual a 5.")
                ->danger()
                ->duration(300000) // 5 minutos (300,000 milisegundos)
                ->icon('heroicon-o-exclamation-triangle')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('ver')
                        ->label('Ver productos con bajo stock')
                        ->url(route('filament.admin.resources.productos.low-stock'))
                        ->button()
                        ->color('danger')
                ])
                ->send();
        }
    }

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
                    ->grow(false),
                TextColumn::make('cantidad')->label('Stock')->sortable(),
                TextColumn::make('categoria.nombre')->label('Categoría')->sortable(),
                TextColumn::make('marca.nombre')->label('Marca')->sortable(),
            ]);
    }
}