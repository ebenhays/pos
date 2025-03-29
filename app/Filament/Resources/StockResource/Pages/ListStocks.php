<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('create stock')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view stock');
    }
}
