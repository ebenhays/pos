<?php

namespace App\Filament\Resources\GovTaxResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\GovTaxResource;

class EditGovTax extends EditRecord
{
    protected static string $resource = GovTaxResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete tax info')) {
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
        return Auth::user()->can('edit tax info');
    }
}
