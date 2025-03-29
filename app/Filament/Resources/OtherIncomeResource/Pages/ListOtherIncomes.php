<?php

namespace App\Filament\Resources\OtherIncomeResource\Pages;

use App\Filament\Resources\OtherIncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListOtherIncomes extends ListRecords
{
    protected static string $resource = OtherIncomeResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('create other income')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];

    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view other income');
    }
}
