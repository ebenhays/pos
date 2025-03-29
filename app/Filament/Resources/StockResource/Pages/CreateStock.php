<?php

namespace App\Filament\Resources\StockResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\StockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['item_qty_remaining'] = $data['opening_stock'];
        return $this->getModel()::create($data);
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('create stock');
    }
}

