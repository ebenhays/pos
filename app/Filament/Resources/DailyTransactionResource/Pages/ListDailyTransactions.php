<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DailyTransactionResource;

class ListDailyTransactions extends ListRecords
{
    protected static string $resource = DailyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('create daily sales')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view daily sales');
    }
}
