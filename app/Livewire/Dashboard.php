<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('total_sales', \App\Models\Sale::count())
                ->label('Total Sales')
                ->description('Total sales made')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            Stat::make('total_customers', \App\Models\Customer::count())
                ->label('Total Customers')
                ->description('Total customers registered')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            Stat::make('total_items_sold', \App\Models\SalesItem::sum('quantity'))
                ->label('Total Items Sold')
                ->description('Total items sold')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('warning'),
            Stat::make('total_revenue', \App\Models\Sale::sum('total_amount'))
                ->label('Total Revenue')
                ->description('Total revenue generated')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('info'),
        ];
    }
}
