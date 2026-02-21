<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\{Inventory, Product, Transaction};

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total product', Product::all()->count()),
            Stat::make('Total stock', Inventory::all()->sum('stock')),
            Stat::make('Total stock in value', Inventory::all()->sum('stock_value')),
            Stat::make('Total daily transaction value', Transaction::where('trx_date', now()->today())->sum('total_price')),
        ];
    }
}
