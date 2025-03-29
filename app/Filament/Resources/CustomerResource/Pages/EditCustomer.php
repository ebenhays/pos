<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CustomerResource;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete customer')) {
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
        return Auth::user()->can('edit customer');
    }
}
