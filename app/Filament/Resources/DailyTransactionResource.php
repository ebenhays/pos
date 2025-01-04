<?php

namespace App\Filament\Resources;

use App\Enum\StockUnitEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Split;
use Filament\Notifications\Notification;
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
                                        Header::make('items')
                                            ->width('150px'),
                                        Header::make('Unit')
                                            ->width('150px'),
                                        Header::make('Price')
                                            ->width('100px'),
                                        Header::make('Qty')
                                            ->width('90px'),
                                        Header::make(name: 'Total')
                                            ->width('95px'),
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
                                            ->required()
                                            ->afterStateUpdated(fn($set) => $set('item_unit', null)),
                                        Select::make('item_unit')
                                            ->options(fn() => ProductSellingType::all()->pluck('selling_type', 'selling_code'))
                                            ->native(false)
                                            ->reactive()
                                            ->searchable()
                                            ->required()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $set('item_price', 0.00);
                                                $set('qty', 0.00);
                                                $stock = Stock::where('id', (int) $get('item_stock'))->first();
                                                switch ($state) {
                                                    case StockUnitEnum::WHOLESALE->value:
                                                        $set('item_price', $stock->sp_wholesale ?? 0.00);
                                                        break;
                                                    case StockUnitEnum::RETAIL->value:
                                                        $set('item_price', $stock->sp_retail ?? 0.00);
                                                        break;
                                                    case StockUnitEnum::BOX->value:
                                                        $set('item_price', $stock->sp_box ?? 0.00);
                                                        break;
                                                    case StockUnitEnum::KILOS->value:
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
                                            ->disabled(fn(callable $get) => (float) $get('item_price') <= 0.00)
                                            ->minValue(1)
                                            ->afterStateUpdated(function ($state, $get, $set) {
                                                $itemTotal = (float) $state * (float) $get('item_price');
                                                $set('item_total', $itemTotal ?? 0.00);
                                            }),

                                        TextInput::make('item_total')
                                            ->disabled()
                                            ->dehydrated()
                                            ->reactive(),
                                    ])
                                    ->columnSpan('full')
                                    ->createItemButtonLabel('Add New Record')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $total = collect($state)
                                            ->map(fn($item) => ((float) $item['item_price'] ?? 0) * ((float) $item['qty'] ?? 1))
                                            ->sum(); // Sum all rows
                            
                                        $set('sub_total', $total);
                                        $taxes = GovTax::get();
                                        $updatedTaxes = $taxes->map(function ($tax) use ($total) {
                                            return [
                                                'name' => $tax->tax_name,
                                                'percentage' => $tax->percentage,
                                                'total_tax' => round((float) $total * ($tax->percentage / 100), 2),
                                            ];
                                        })->toArray();
                                        $set('Taxes', $updatedTaxes);
                                        $totalTaxes = collect($updatedTaxes)->sum('total_tax');
                                        $set('total_taxes', round($totalTaxes, 2));
                                        $set('amount_due', round((float) ($total + $totalTaxes), 2));
                                        $set('original_amt_due', round((float) ($total + $totalTaxes), 2));
                                    })
                            ]),

                        Section::make('Summary')
                            ->columns()
                            ->schema([
                                TextInput::make('sub_total')
                                    ->disabled()
                                    ->label('SubTotal')
                                    ->dehydrated()
                                    ->reactive(),
                                TextInput::make('total_taxes')
                                    ->numeric()
                                    ->disabled()
                                    ->label('Total Tax')
                                    ->dehydrated()
                                    ->reactive()
                                    ->default(0.00),
                                TableRepeater::make('Taxes')
                                    ->columns(2)
                                    ->headers([
                                        Header::make('tax name'),
                                        Header::make('percentage'),
                                        Header::make('total'),
                                    ])
                                    ->reactive()
                                    ->columnSpanFull()
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
                                            ->reactive()
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
                                                'total_tax' => round($subTotal * ($tax->percentage / 100), 2),
                                            ];
                                        })->toArray();
                                    })
                                    ->disableItemCreation()
                                    ->disableItemDeletion()
                                    ->required(),

                            ]),

                        Section::make('Billing')
                            ->columns()
                            ->schema([
                                Select::make('payment_type')
                                    ->relationship('paymentType', 'name')
                                    ->required()
                                    ->native(false)
                                    ->columnSpanFull(),

                                TextInput::make('amount_due')
                                    ->numeric()
                                    ->disabled()
                                    ->label('Amount Due')
                                    ->dehydrated()
                                    ->reactive()
                                    ->default(0.00),
                                TextInput::make('discount')
                                    ->numeric()
                                    ->reactive()
                                    ->minValue(0)
                                    ->label('Discount(Amount only)')
                                    ->default(0.00)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $totalDue = (float) $get('original_amt_due') - (float) $state;
                                        $set('amount_due', round($totalDue, 2));
                                    }),
                                TextInput::make('customer_amount')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->default(0)
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        if ((float) $state <= 0) {
                                            return Notification::make()
                                                ->title('Please check customer amount')
                                                ->danger()
                                                ->color('danger')
                                                ->send();
                                        }
                                        $totalAmtPayable = (float) $get('amount_due');
                                        if ((float) $state < $totalAmtPayable) {
                                            return Notification::make()
                                                ->title('customer amount is less than amount due')
                                                ->danger()
                                                ->color('danger')
                                                ->send();
                                        }

                                        $set('customer_change', round((float) $state - $totalAmtPayable, 2));



                                    }),
                                TextInput::make('customer_change')
                                    ->disabled()
                                    ->dehydrated()
                                    ->reactive()
                                    ->default(0.00),

                                Hidden::make('original_amt_due')
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
