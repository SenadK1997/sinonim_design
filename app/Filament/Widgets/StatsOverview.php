<?php

namespace App\Filament\Widgets;

use App\Models\ManualSale;
use App\Models\Order;
use App\Models\Product;
use App\Support\Money;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $monthStart = now()->startOfMonth();

        $revenueOrders = (float) Order::query()
            ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
            ->where('created_at', '>=', $monthStart)
            ->sum('total');

        $revenueManual = (float) ManualSale::query()
            ->where('sold_at', '>=', $monthStart)
            ->sum('amount');

        $totalRevenue = $revenueOrders + $revenueManual;

        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();

        $publishedProducts = Product::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        return [
            Stat::make('Prihod ovaj mjesec', Money::format($totalRevenue))
                ->description('Web + ručne prodaje')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Nove narudžbe', $pendingOrders)
                ->description('Čekaju obradu')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Objavljeni proizvodi', $publishedProducts)
                ->description('Ukupno u prodavnici')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary'),
        ];
    }
}
