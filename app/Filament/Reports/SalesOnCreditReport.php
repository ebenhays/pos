<?php

namespace App\Filament\Reports;

use Carbon\Carbon;
use Filament\Forms\Form;
use EightyNine\Reports\Report;
use App\Models\DailyTransaction;
use Illuminate\Support\Facades\Auth;
use EightyNine\Reports\Enums\FontSize;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use App\Models\SalesOnCreditTransactions;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use Filament\Forms\Components\DatePicker;
use EightyNine\Reports\Components\Body\TextColumn;
use App\Filament\Resources\DailyTransactionResource\Pages\CreateDailyTransaction;

class SalesOnCreditReport extends Report
{
    public ?string $heading = "Report";

    public ?string $subHeading = "Sales on credit Report";

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
                                Text::make("Sales on credit Report")
                                    ->title()
                                    ->primary(),
                                Text::make("Customer purchase on credit")
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
                                TextColumn::make('Batch')
                                    ->groupRows(),
                                TextColumn::make('Customer'),
                                TextColumn::make('Date'),

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
        $query = SalesOnCreditTransactions::with(['customer']);

        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Fetch and transform the data
        $reportData = $query->get()->map(function ($transaction) {
            return [
                'Batch' => $transaction->batch_no,
                'Customer' => $transaction->customer->name,
                'Date' => $transaction->created_at,
            ];
        });

        return $reportData;
    }

    public function canViewAny(): bool
    {
        return Auth::user()->can('view sales on credit report');
    }

}
