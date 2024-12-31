<?php

namespace App\Filament\Resources\GovTaxResource\Pages;

use App\Filament\Resources\GovTaxResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGovTax extends CreateRecord
{
    protected static string $resource = GovTaxResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
