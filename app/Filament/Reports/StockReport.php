<?php

namespace App\Filament\Reports;

use App\Models\Stock;
use Carbon\Carbon;
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

class StockReport extends Report
{
    public ?string $heading = "Report";

    public ?string $subHeading = "Stock Report";

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
                                Text::make("Stock Report")
                                    ->title()
                                    ->primary(),
                                Text::make("Stock listing")
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
                                TextColumn::make('Category')
                                    ->groupRows(),

                                TextColumn::make('Item No'),
                                TextColumn::make('Item'),
                                TextColumn::make('Unit'),
                                TextColumn::make('Qty'),
                                TextColumn::make('Qty Left'),
                                TextColumn::make('Cost Price'),
                                TextColumn::make('Total Stock'),
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

        $query = Stock::with('category');

        $query->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc');

        // Fetch and transform the data
        $reportData = $query->get()->map(function ($transaction) {
            return [
                'Category' => $transaction->category->name,
                'Item' => $transaction->item,
                'Item No' => $transaction->item_no,
                'Unit' => $transaction->item_unit_code,
                'Qty' => $transaction->opening_stock,
                'Qty Left' => $transaction->item_qty_remaining,
                'Cost Price' => $transaction->item_cost_price,
                'Total Stock' => $transaction->total_stock,
            ];
        });
        return $reportData;
    }

    public function canViewAny(): bool
    {
        return Auth::user()->can('view product sales report');
    }

}
