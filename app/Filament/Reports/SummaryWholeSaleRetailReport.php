<?php

namespace App\Filament\Reports;

use App\Models\Expense;
use Carbon\Carbon;
use Filament\Forms\Form;
use App\Enum\StockUnitEnum;
use EightyNine\Reports\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyTransactionSummary;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use Filament\Forms\Components\DatePicker;
use EightyNine\Reports\Components\Body\TextColumn;

class SummaryWholeSaleRetailReport extends Report
{
    public ?string $heading = "Report";

    public ?string $subHeading = "Summary Wholesale And Retail Report";

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
                                Text::make("Summary Report")
                                    ->title()
                                    ->primary(),
                                Text::make("Sales From Wholesale And Retail")
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
                                TextColumn::make('Wholesale'),
                                TextColumn::make('Retail'),
                                TextColumn::make('COS Wholesale'),
                                TextColumn::make('COS Retail'),
                                TextColumn::make('Profit Wholesale'),
                                TextColumn::make('Profit Retail'),
                                TextColumn::make('Total Profit'),
                                // TextColumn::make('Other Income'),
                                TextColumn::make('Gross Profit'),
                                TextColumn::make('Expenses'),
                                TextColumn::make('Net Profit'),
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
        $startDate = !empty($filters['start_date']) ? Carbon::parse($filters['start_date'])->startOfDay() : today()->startOfDay();
        $endDate = !empty($filters['end_date']) ? Carbon::parse($filters['end_date'])->endOfDay() : today()->endOfDay();
        $results = DB::table('daily_transaction_summaries')
            ->selectRaw('
            transaction_date,
            SUM(total_wholesale_sales) AS total_wholesale,
            SUM(total_retail_sales) as total_retail,
            SUM(COS_wholesale) as total_cos_wholesale,
            SUM(COS_retail) AS total_cos_retail,
            SUM(total_profit_wholesale) AS total_profit_wholesale,
            SUM(total_profit_retail) AS total_profit_retail,
            SUM(other_income_wholesale_retail) AS total_other_income_wholesale_retail,
            SUM(gross_profit_wholesale_retail) AS total_gross_profit_wholesale_retail,
            SUM(expenses_wholesale_retail) AS total_expenses_wholesale_retail,
            SUM(net_profit_wholesale_retail) AS total_net_profit_wholesale_retail
        ')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();

        $totalExpWholesale = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('tied_to', StockUnitEnum::WHOLESALE->value)->sum('amount');
        $totalExpRetail = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('tied_to', StockUnitEnum::RETAIL->value)->sum('amount');
        $reportData = $results->map(function ($transaction) use ($totalExpWholesale, $totalExpRetail) {
            $formattedTotal = number_format($totalExpWholesale + $totalExpRetail, 2, '.', ',');
            return [
                'Date' => $transaction->transaction_date,
                'Wholesale' => $transaction->total_wholesale,
                'Retail' => $transaction->total_retail,
                'COS Wholesale' => $transaction->total_cos_wholesale,
                'COS Retail' => $transaction->total_cos_retail,
                'Profit Wholesale' => $transaction->total_profit_wholesale,
                'Profit Retail' => $transaction->total_profit_retail,
                'Total Profit' => $transaction->total_profit_wholesale + $transaction->total_profit_retail,
                // 'Other Income' => $transaction->total_other_income_wholesale_retail,
                'Gross Profit' => $transaction->total_gross_profit_wholesale_retail,
                'Expenses' => $formattedTotal,
                'Net Profit' => $transaction->total_gross_profit_wholesale_retail - ($totalExpWholesale + $totalExpRetail),
            ];
        });
        return $reportData;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view summary(wholesale/retail) report');
    }
}
