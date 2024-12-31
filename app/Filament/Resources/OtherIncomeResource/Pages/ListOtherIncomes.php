<?php

namespace App\Filament\Resources\OtherIncomeResource\Pages;

use App\Filament\Resources\OtherIncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOtherIncomes extends ListRecords
{
    protected static string $resource = OtherIncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
