<?php

namespace App\Filament\Reports;

use Carbon\Carbon;
use App\Models\Expense;
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

class SummaryKilosBoxReport extends Report
{
    public ?string $heading = "Report";

    public ?string $subHeading = "Summary Kilos And Box Report";

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
                                Text::make("Sales From Kilos And Box")
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
                                TextColumn::make('Sales Box'),
                                TextColumn::make('Sales Kilos'),
                                TextColumn::make('COS Box'),
                                TextColumn::make('COS Kilos'),
                                TextColumn::make('Profit Box'),
                                TextColumn::make('Profit Kilos'),
                                TextColumn::make('Total Profit'),
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
            SUM(total_sales_in_box) as total_sales_box,
            SUM(total_sales_in_kilos) AS total_sales_kg,
            SUM(COS_box) AS total_cos_box,
            SUM(COS_kilos) AS total_cos_kilos,
            SUM(total_profit_in_box) AS total_profit_box,
            SUM(total_profit_in_kilos) AS total_profit_kilos,
            SUM(expenses_box_kilos) AS total_expenses_box_kilos,
            SUM(net_profit_box_kilos) AS total_net_profit_box_kilos,
            SUM(other_income_box_kilos) AS total_other_income_box_kilos,
            SUM(gross_profilt_box_kilos)AS total_gross_profilt_box_kilos
        ')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();

        $totalExpenseBox = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('tied_to', StockUnitEnum::BOX->value)->sum('amount');
        $totalExpenseKilos = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('tied_to', StockUnitEnum::KILOS->value)->sum('amount');
        $reportData = $results->map(function ($transaction) use ($totalExpenseBox, $totalExpenseKilos) {
            $formattedTotal = number_format($totalExpenseBox + $totalExpenseKilos, 2, '.', ',');
            return [
                'Date' => $transaction->transaction_date,
                'Sales Box' => $transaction->total_sales_box,
                'Sales Kilos' => $transaction->total_sales_kg,
                'COS Box' => $transaction->total_cos_box,
                'COS Kilos' => $transaction->total_cos_kilos,
                'Profit Box' => $transaction->total_profit_box,
                'Profit Kilos' => $transaction->total_profit_kilos,
                'Total Profit' => $transaction->total_profit_box + $transaction->total_profit_kilos,
                'Gross Profit' => $transaction->total_gross_profilt_box_kilos,
                'Expenses' => $formattedTotal,
                'Net Profit' => $transaction->total_gross_profilt_box_kilos - ($totalExpenseBox + $totalExpenseKilos),
            ];
        });
        return $reportData;
    }

    public function canViewAny(): bool
    {
        return Auth::user()->can('view summary(kilos/box) report');
    }
}
