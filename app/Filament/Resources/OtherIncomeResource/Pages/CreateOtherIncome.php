<?php

namespace App\Filament\Resources\OtherIncomeResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\OtherIncomeResource;

class CreateOtherIncome extends CreateRecord
{
    protected static string $resource = OtherIncomeResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('create other income');
    }
}
