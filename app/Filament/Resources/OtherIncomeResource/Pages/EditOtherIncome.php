<?php

namespace App\Filament\Resources\OtherIncomeResource\Pages;

use App\Filament\Resources\OtherIncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOtherIncome extends EditRecord
{
    protected static string $resource = OtherIncomeResource::class;

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
