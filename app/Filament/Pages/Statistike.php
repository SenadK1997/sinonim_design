<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Statistike extends Page
{
    protected string $view = 'filament.pages.statistike';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Statistike';

    protected static string|\UnitEnum|null $navigationGroup = 'Prodaja';

    protected static ?int $navigationSort = 0;

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 4;
    }

    public function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\RevenueChart::class,
            \App\Filament\Widgets\OrdersByStatusChart::class,
            \App\Filament\Widgets\SalesByChannelChart::class,
            \App\Filament\Widgets\TopProducts::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 2;
    }
}
