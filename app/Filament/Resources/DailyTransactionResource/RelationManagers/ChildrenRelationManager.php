<?php

namespace App\Filament\Resources\DailyTransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DailyTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';
    protected static ?string $title = 'Sales Items';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('batch_no')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('batch_no')
            ->columns([
                Tables\Columns\TextColumn::make('batch_no'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Sales Date'),
                Tables\Columns\TextColumn::make('stock.item')
                    ->label('Item')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make(name: 'qty_sold')
                    ->label('Qty'),
                Tables\Columns\TextColumn::make('item_amount')
                    ->label('Price'),
                Tables\Columns\TextColumn::make('total_per_item')
                    ->label('Total'),
                Tables\Columns\TextColumn::make('selling_code')
                    ->label('Unit'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cashier')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id');
    }

}
