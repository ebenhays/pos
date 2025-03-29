<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomerResource;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('create customer');
    }
}
