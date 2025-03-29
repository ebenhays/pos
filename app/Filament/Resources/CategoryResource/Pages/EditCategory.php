<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('delete category')) {
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
        return Auth::user()->can('edit category');
    }
}
