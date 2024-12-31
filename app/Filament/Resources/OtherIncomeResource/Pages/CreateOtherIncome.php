<?php

namespace App\Filament\Resources\OtherIncomeResource\Pages;

use App\Filament\Resources\OtherIncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOtherIncome extends CreateRecord
{
    protected static string $resource = OtherIncomeResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
