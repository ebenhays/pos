<?php

namespace App\Filament\Reports;

use Filament\Forms\Form;
use EightyNine\Reports\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\DailyTransactionSummary;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use Filament\Forms\Components\DatePicker;
use EightyNine\Reports\Components\Body\TextColumn;

class ProductSalesReport extends Report
{
    public ?string $heading = "Report";

    public ?string $subHeading = "Product Sales Report";

    public function header(Header $header): Header
    {
        if (!$this->canViewAny()) {
            return $header->schema([]);
        }
        return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                    ->schema([
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Text::make("Product Sales Summary Report")
                                    ->title()
                                    ->primary(),
                                Text::make("Total Transactions by Unit")
                                    ->subtitle()
                            ])->alignCenter(),
                    ]),
            ]);
    }


    public function body(Body $body): Body
    {
        if (!$this->canViewAny()) {
            return $body->schema([]);
        }
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        Body\Table::make()
                            ->data(
                                fn(?array $filters) => $this->getReportData($filters)
                            )
                            ->columns([
                                TextColumn::make('Date')
                                    ->groupRows(),
                                TextColumn::make('Item'),
                                TextColumn::make('Wholesale')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('COS Wholesale')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('Retail')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('COS Retail')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('Box')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('COS Box')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('Kilos')
                                    ->sum()
                                    ->money('GHS'),
                                TextColumn::make('COS Kilos')
                                    ->sum()
                                    ->money('GHS')
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
        if (!$this->canViewAny()) {
            return $form->schema([]);
        }
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

        $query = DailyTransactionSummary::with('stock');

        $query->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'asc');

        // Fetch and transform the data
        $reportData = $query->get()->map(function ($transaction) {
            return [
                'Date' => $transaction->transaction_date,
                'Item' => $transaction->stock->item,
                'Wholesale' => $transaction->total_wholesale_sales,
                'COS Wholesale' => $transaction->COS_wholesale,
                'Retail' => $transaction->total_retail_sales,
                'COS Retail' => $transaction->COS_retail,
                'Box' => $transaction->total_sales_in_box,
                'COS Box' => $transaction->COS_box,
                'Kilos' => $transaction->total_sales_in_kilos,
                'COS Kilos' => $transaction->COS_kilos,
            ];
        });
        return $reportData;
    }

    public function canViewAny(): bool
    {
        return Auth::user()->can('view product sales report');
    }

}
