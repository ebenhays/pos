<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CategoryResource;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->can('create category')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view category');
    }
}
