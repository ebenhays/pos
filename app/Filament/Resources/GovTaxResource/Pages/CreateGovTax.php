<?php

namespace App\Filament\Resources\GovTaxResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\GovTaxResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGovTax extends CreateRecord
{
    protected static string $resource = GovTaxResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function canViewAny(): bool
    {
        return Auth::user()->can('create tax info');
    }
}
