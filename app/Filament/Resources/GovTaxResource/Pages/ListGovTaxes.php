<?php

namespace App\Filament\Resources\GovTaxResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GovTaxResource;

class ListGovTaxes extends ListRecords
{
    protected static string $resource = GovTaxResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('create tax info')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view tax info');
    }
}
