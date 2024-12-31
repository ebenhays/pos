<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Stock;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductUnit;
use App\Enum\ProductUnitEnum;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StockResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StockResource\RelationManagers;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create New Stock')
                    ->description('Add new stock')
                    ->columns()
                    ->schema([
                        TextInput::make(name: 'item_no')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn() => "ST" . date("Ym") . substr(md5(uniqid(mt_rand(), true)), 0, 6)),
                        TextInput::make('item')
                            ->required()
                            ->label('Item Name')
                            ->maxLength(100),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Select::make('item_unit_code')
                            ->options(fn() =>
                                ProductUnit::all()->pluck('prod_desc', 'prod_unit_code'))
                            ->label('Prod Unit')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                if (in_array((int) $state, [ProductUnitEnum::SINGLE_ITEM->value, ProductUnitEnum::BOTTLE->value])) {
                                    $set('disable_sp_wholesale', true);
                                    $set('disable_sp_box', true);
                                    $set('disable_sp_kg', true);
                                } else {
                                    $set('disable_sp_wholesale', false);
                                    $set('disable_sp_box', false);
                                    $set('disable_sp_kg', false);
                                }
                            }),
                        TextInput::make('type')
                            ->label('Item type')
                            ->datalist([
                                'Regular',
                                'Hungry Man',
                                'Belle full',
                                'Super Pack',
                                'Others'
                            ]),
                        TextInput::make('opening_stock')
                            ->required()
                            ->minValue(1)
                            ->numeric()
                            ->reactive()
                            ->default(0),
                        TextInput::make('item_cost_price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Cost Price')
                            ->default(0.00),

                        TextInput::make('sp_wholesale')
                            ->required()
                            ->numeric()
                            ->dehydrated()
                            ->disabled(fn($get) => $get('disable_sp_wholesale'))
                            ->minValue(0)
                            ->label('Selling Price(Wholesale)')
                            ->default(0.00),
                        TextInput::make('sp_retail')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Selling Price(Retail)')
                            ->default(0.00),
                        TextInput::make('sp_box')
                            ->required()
                            ->disabled(fn($get) => $get('disable_sp_box'))
                            ->minValue(0)
                            ->numeric()
                            ->label('Selling Price(box)')
                            ->default(0.00),
                        TextInput::make('sp_kg')
                            ->required()
                            ->disabled(fn($get) => $get('disable_sp_kg'))
                            ->numeric()
                            ->minValue(0)
                            ->label('Selling Price(kilos)')
                            ->default(0.00),
                        TextInput::make('additions')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        DatePicker::make('manufacture_date')
                            ->native(false),
                        DatePicker::make('expiry_date')
                            ->native(false),
                        TextInput::make('fda_no')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_no')
                    ->searchable(),
                TextColumn::make('item')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('opening_stock')
                    ->numeric()
                    ->label('Stock Qty')
                    ->sortable(),
                TextColumn::make('item_qty_remaining')
                    ->numeric()
                    ->label('Qty left')
                    ->sortable(),
                TextColumn::make('item_cost_price')
                    ->numeric()
                    ->label('Cost Price')
                    ->sortable(),
                TextColumn::make('sp_wholesale')
                    ->numeric()
                    ->label('Wholesale(SP)')
                    ->sortable(),
                TextColumn::make('sp_retail')
                    ->numeric()
                    ->label('Retail(SP)')
                    ->sortable(),
                TextColumn::make('sp_box')
                    ->numeric()
                    ->label('SP(Box)')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('sp_kg')
                    ->numeric()
                    ->label('SP(Kg)')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('manufacture_date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('fda_no')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('additions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cp_box')
                    ->numeric()
                    ->label('CostPrice(Box)')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('cp_kg')
                    ->numeric()
                    ->label('CostPrice(Kg)')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('total_stock')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
