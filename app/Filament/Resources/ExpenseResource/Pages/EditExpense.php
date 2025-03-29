<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ExpenseResource;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete expense')) {
            return [
                Actions\DeleteAction::make(),
            ];
        }
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('edit expense');
    }
}
