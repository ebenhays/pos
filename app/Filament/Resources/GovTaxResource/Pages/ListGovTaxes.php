<?php

namespace App\Filament\Resources\GovTaxResource\Pages;

use App\Filament\Resources\GovTaxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGovTaxes extends ListRecords
{
    protected static string $resource = GovTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
