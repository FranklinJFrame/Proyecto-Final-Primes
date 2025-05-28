<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use App\Filament\Resources\ProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class LowStockProductos extends ListRecords
{
    protected static string $resource = ProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        // Solo productos con stock bajo (0 a 5)
        return parent::getTableQuery()?->whereBetween('cantidad', [0, 5]);
    }

    public function getTitle(): string
    {
        return 'Productos con Bajo Stock';
    }
} 