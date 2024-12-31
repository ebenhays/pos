<?php

namespace App\Filament\Resources\GovTaxResource\Pages;

use App\Filament\Resources\GovTaxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGovTax extends EditRecord
{
    protected static string $resource = GovTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
