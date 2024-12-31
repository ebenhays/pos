<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use App\Models\Stock;
use App\Models\GovTax;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DailyTransaction;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use App\Models\ProductSellingType;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DailyTransactionResource\Pages;
use App\Filament\Resources\DailyTransactionResource\RelationManagers;

class DailyTransactionResource extends Resource
{
    protected static ?string $model = DailyTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sales Screen')
                    ->description('This is where sales goes on')
                    ->columns()
                    ->schema([
                        Section::make('Sales Information')
                            ->schema([
                                TableRepeater::make('sales_info')
                                    ->headers([
                                        Header::make('items')->align(Alignment::Center),
                                        Header::make('Unit')->align(Alignment::Center),
                                        Header::make('Price')->align(Alignment::Center),
                                        Header::make('Qty')->align(Alignment::Center),
                                        Header::make(name: 'Total')->align(Alignment::Center),
                                    ])
                                    ->schema([
                                        Select::make('item_stock')
                                            ->relationship('stock', 'item', function ($query) {
                                                $query->where('item_qty_remaining', '>', 0);
                                            })
                                            ->label('Search Item')
                                            ->preload()
                                            ->reactive()
                                            ->searchable()
                                            ->afterStateUpdated(fn($set) => $set('item_unit', null)),
                                        Select::make('item_unit')
                                            ->options(fn() => ProductSellingType::all()->pluck('selling_type', 'selling_code'))
                                            ->native(false)
                                            ->reactive()
                                            ->searchable()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $stock = Stock::where('id', (int) $get('item_stock'))->first();
                                                switch ($state) {
                                                    case 'WHL':
                                                        $set('item_price', $stock->sp_wholesale ?? 0.00);
                                                        break;
                                                    case 'RTL':
                                                        $set('item_price', $stock->sp_retail ?? 0.00);
                                                        break;
                                                    case 'BX':
                                                        $set('item_price', $stock->sp_box ?? 0.00);
                                                        break;
                                                    case 'KG':
                                                        $set('item_price', $stock->sp_kg ?? 0.00);
                                                        break;
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                            }),
                                        TextInput::make('item_price')
                                            ->default(fn($get) => $get('item_price'))
                                            ->disabled()
                                            ->dehydrated(),
                                        TextInput::make('qty')
                                            ->numeric()
                                            ->required()
                                            ->reactive()
                                            ->minValue(1)
                                            ->afterStateUpdated(function ($state, $get, $set) {
                                                $itemTotal = (float) $state * (float) $get('item_price');
                                                $set('item_total', $itemTotal);
                                            }),

                                        TextInput::make('item_total')
                                            ->default(fn($get) => $get('item_total'))
                                            ->disabled()
                                            ->dehydrated(),
                                    ])
                                    ->columnSpan('full')
                                    ->createItemButtonLabel('Add New Record')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $total = collect($state)
                                            ->map(fn($item) => ($item['item_price'] ?? 0) * ($item['qty'] ?? 1))
                                            ->sum(); // Sum all rows
                            
                                        $set('sub_total', $total);
                                    })
                            ]),

                        Section::make('Summary')
                            ->columns()
                            ->schema([
                                TextInput::make('sub_total')
                                    ->disabled()
                                    ->label('SubTotal')
                                    ->dehydrated()
                                    ->reactive()
                                    ->default(fn($get) => $get('sub_total'))
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        // Update Taxes when sub_total changes
                                        $taxes = $get('Taxes') ?? [];
                                        $updatedTaxes = collect($taxes)->map(function ($tax) use ($state) {
                                            $tax['total_tax'] = $state * ($tax['percentage'] / 100);
                                            return $tax;
                                        })->toArray();
                                        $set('Taxes', $updatedTaxes);
                                    }),
                                TextInput::make('discount')
                                    ->numeric()
                                    ->default(0.00),
                                TableRepeater::make('Taxes')
                                    ->columns(2)
                                    ->headers([
                                        Header::make('tax name'),
                                        Header::make('percentage'),
                                        Header::make('total'),
                                    ])
                                    ->reactive()
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('tax')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                        TextInput::make('percentage')
                                            ->label('percentage')
                                            ->disabled()
                                            ->dehydrated()
                                            ->numeric()
                                            ->required(),
                                        TextInput::make('total_tax')
                                            ->label('total')
                                            ->numeric()
                                            ->disabled()
                                            ->default(0.00)
                                            ->dehydrated()
                                            ->required(),
                                    ])
                                    ->default(function ($get) {
                                        $taxes = GovTax::get();
                                        $subTotal = $get('sub_total') ?? 0;
                                        return $taxes->map(function ($tax) use ($subTotal) {
                                            return [
                                                'name' => $tax->tax_name,
                                                'percentage' => $tax->percentage,
                                                'total_tax' => $subTotal * ($tax->percentage / 100),
                                            ];
                                        })->toArray();
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $subTotal = $get('sub_total') ?? 0;
                                        $updatedTaxes = collect($state)->map(function ($tax) use ($subTotal) {
                                            $tax['total_tax'] = $subTotal * ($tax['percentage'] / 100);
                                            return $tax;
                                        })->toArray();

                                        $set('Taxes', $updatedTaxes); // Update the repeater state
                                    })
                                    ->disableItemCreation()
                                    ->disableItemDeletion()
                                    ->required()

                            ])

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentType.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_tendered')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change_given')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_wholesale_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_retail_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_wholesale_retail_qty_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_wholesale_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_retail_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_wholesale_retail_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_box_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_kg_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_qty_box_sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_qty_kg_sold')
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
            'index' => Pages\ListDailyTransactions::route('/'),
            'create' => Pages\CreateDailyTransaction::route('/create'),
            'edit' => Pages\EditDailyTransaction::route('/{record}/edit'),
        ];
    }
}
