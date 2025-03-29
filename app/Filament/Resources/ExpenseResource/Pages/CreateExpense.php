<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ExpenseResource;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('create expense');
    }
}
