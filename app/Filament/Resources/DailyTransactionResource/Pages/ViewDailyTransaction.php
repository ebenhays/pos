<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use App\Filament\Resources\DailyTransactionResource;
use App\Models\DailyTransaction;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyTransaction extends ViewRecord
{
    protected static string $resource = DailyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->url(fn() => route('invoice-print', ['batch_no' => $this->record->batch_no]), true)
                ->label('Print')
                ->color('success')
                ->icon('heroicon-o-printer'),
        ];
    }
}

