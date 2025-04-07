<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\DailyTransaction;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currency_symbol = "GHS";

        $totalSalesLast30Days = DailyTransaction::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $totalIncomeLast30Days = DailyTransaction::where('created_at', '>=', Carbon::now()->subDays(30))->sum('total_per_item');
        $totalcustomersLast30Days = Customer::where('created_at', '<=', Carbon::now()->subDays(30))->count();

        return [
            Stat::make('Sales Count', $totalSalesLast30Days)
                ->description("Total sales count in the last 30 days")
                ->descriptionIcon('heroicon-o-inbox-stack', IconPosition::Before)
                ->chart([1, 5, 10, 50])
                ->color('success'),

            Stat::make('Sales Income', $currency_symbol . ' ' . $totalIncomeLast30Days)
                ->description("Total sales income in the last 30 days")
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->chart([1, 5, 30, 50])
                ->color('success'),

            Stat::make('Customers Count', $totalcustomersLast30Days)
                ->description("Last 30 days customers count")
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->chart([1, 5, 15, 25])
                ->color('success'),
        ];
    }
}
