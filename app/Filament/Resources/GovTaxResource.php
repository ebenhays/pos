<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\GovTax;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GovTaxResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GovTaxResource\RelationManagers;

class GovTaxResource extends Resource
{
    protected static ?string $model = GovTax::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Government Taxes')
                    ->icon('heroicon-o-information-circle')
                    ->description('Taxes to be charged on items')
                    ->schema([
                        TextInput::make('tax_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('percentage')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tax_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGovTaxes::route('/'),
            'create' => Pages\CreateGovTax::route('/create'),
            'edit' => Pages\EditGovTax::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view tax info');
    }
}
