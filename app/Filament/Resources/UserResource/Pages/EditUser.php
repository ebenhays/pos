<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete users')) {
            return [
                Actions\DeleteAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('edit users');
    }
}
