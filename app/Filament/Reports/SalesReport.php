<?php

namespace App\Filament\Reports;

use Filament\Forms\Form;
use EightyNine\Reports\Report;
use App\Models\DailyTransaction;
use EightyNine\Reports\Enums\FontSize;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use Filament\Forms\Components\DatePicker;
use EightyNine\Reports\Components\Body\TextColumn;

class SalesReport extends Report
{
    public ?string $heading = "Report";

    public ?string $subHeading = "Daily Sales Report";

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                    ->schema([
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Text::make("Daily Sales Report")
                                    ->title()
                                    ->primary(),
                                Text::make("Sales records grouped by batch")
                                    ->subtitle()
                            ])->alignCenter(),
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Image::make(''),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        Body\Table::make()
                            ->data(
                                fn(?array $filters) => $this->getReportData($filters)
                            )
                            ->columns([
                                TextColumn::make('Batch')
                                    ->groupRows(),
                                TextColumn::make('Date'),
                                TextColumn::make('Item'),
                                TextColumn::make('Pmt'),
                                TextColumn::make('Unit'),
                                TextColumn::make('Price'),
                                TextColumn::make('Qty'),
                                TextColumn::make('Total'),
                                TextColumn::make('Cashier'),
                            ]),

                    ]),
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                Footer\Layout\FooterRow::make()
                    ->schema([
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                Text::make("Pointel Systems")
                                    ->subtitle(),
                            ]),
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                Text::make("Generated on: " . now()->format('Y-m-d H:i:s')),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->native(false)
                    ->placeholder('Select start date')
                    ->default(now()->toDateString())
                    ->required(),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->native(false)
                    ->placeholder('Select end date')
                    ->default(now()->toDateString())
                    ->required(),
            ]);
    }

    public function getReportData($filters)
    {
        $startDate = !empty($filters['start_date']) ? $filters['start_date'] : today()->toDateString();
        $endDate = !empty($filters['end_date']) ? $filters['end_date'] : today()->toDateString();

        $query = DailyTransaction::with(['stock', 'paymentType', 'user']);

        $query->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('batch_no', 'asc');

        // Fetch and transform the data
        $reportData = $query->get()->map(function ($transaction) {
            return [
                'Batch' => $transaction->batch_no,
                'Date' => $transaction->transaction_date,
                'Item' => $transaction->stock->item,
                'Pmt' => $transaction->paymentType->Name,
                'Unit' => $transaction->selling_code,
                'Price' => $transaction->item_amount,
                'Qty' => $transaction->qty_sold,
                'Total' => $transaction->total_per_item,
                'Cashier' => $transaction->user?->name
            ];
        });

        return $reportData;
    }

}
