<?php

namespace App\Filament\Resources\DailyTransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CustomerCreditTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'salesOnCreditTransactions';

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
                TextColumn::make('batch_no'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()

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
}
