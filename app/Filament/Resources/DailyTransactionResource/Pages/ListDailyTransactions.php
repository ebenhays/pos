<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use App\Filament\Resources\DailyTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyTransactions extends ListRecords
{
    protected static string $resource = DailyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
