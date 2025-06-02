<?php

namespace App\Filament\Widgets;

use App\Models\Producto;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class LowStockProductos extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $shouldRegister = true;
    protected static ?int $pollingInterval = 60; // Actualizar cada 60 segundos

    public function mount(): void
    {
        $count = Producto::where('cantidad', '<=', 5)->count();
        
        if ($count > 0) {
            Notification::make()
                ->title('¡Atención!')
                ->body("Hay {$count} producto(s) con stock menor o igual a 5.")
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->persistent()
                ->actions([
                    Action::make('ver')
                        ->label('Ver productos con bajo stock')
                        ->button()
                        ->url(route('filament.admin.resources.productos.low-stock'))
                        ->openUrlInNewTab(false)
                        ->color('danger'),
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
                    ->grow(false)
                    ->extraAttributes(fn ($record) => [
                        'class' => 'bg-green-100 dark:bg-green-900/40 font-semibold'
                    ]),
                TextColumn::make('cantidad')->label('Stock')->sortable(),
                TextColumn::make('categoria.nombre')->label('Categoría')->sortable(),
                TextColumn::make('marca.nombre')->label('Marca')->sortable(),
            ]);
    }
}