<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use App\Filament\Resources\DailyTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyTransaction extends EditRecord
{
    protected static string $resource = DailyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
