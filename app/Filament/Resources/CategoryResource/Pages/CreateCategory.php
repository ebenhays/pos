<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('create category');
    }
}
