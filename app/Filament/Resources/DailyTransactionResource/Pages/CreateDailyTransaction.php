<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use App\Filament\Resources\DailyTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyTransaction extends CreateRecord
{
    protected static string $resource = DailyTransactionResource::class;
}
