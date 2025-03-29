<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ExpenseResource;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('create expense')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view expense');
    }
}
