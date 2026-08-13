<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class Statistike extends Dashboard
{
    protected static string $routePath = '/statistike';

    protected static ?string $slug = 'statistike';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Statistike';

    protected static ?string $title = 'Statistike';

    protected static string|\UnitEnum|null $navigationGroup = 'Prodaja';

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\RevenueChart::class,
            \App\Filament\Widgets\OrdersByStatusChart::class,
            \App\Filament\Widgets\SalesByChannelChart::class,
            \App\Filament\Widgets\TopProducts::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
