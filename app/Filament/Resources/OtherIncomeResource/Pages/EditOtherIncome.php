<?php

namespace App\Filament\Resources\OtherIncomeResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OtherIncomeResource;

class EditOtherIncome extends EditRecord
{
    protected static string $resource = OtherIncomeResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete other income')) {
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
        return Auth::user()->can('edit other income');
    }
}
