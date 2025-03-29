<?php

namespace App\Filament\Resources\DailyTransactionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DailyTransactionResource;

class EditDailyTransaction extends EditRecord
{
    protected static string $resource = DailyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete daily sales')) {
            return [
                Actions\DeleteAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('edit daily sales');
    }
}
