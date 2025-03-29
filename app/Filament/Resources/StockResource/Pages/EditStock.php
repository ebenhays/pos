<?php

namespace App\Filament\Resources\StockResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\StockResource;

class EditStock extends EditRecord
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete stock')) {
            return [
                Actions\DeleteAction::make(),
            ];
        }
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('edit stock');
    }
}