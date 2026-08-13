<?php

namespace App\Filament\Widgets;

use App\Models\ManualSale;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Prihod — zadnjih 30 dana';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $webRevenue = [];
        $manualRevenue = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = now()->startOfDay()->subDays($i);
            $labels[] = $day->format('d.m.');

            $webRevenue[] = (float) Order::query()
                ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
                ->whereDate('created_at', $day)
                ->sum('total');

            $manualRevenue[] = (float) ManualSale::query()
                ->whereDate('sold_at', $day)
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Web narudžbe',
                    'data' => $webRevenue,
                    'borderColor' => '#7d6045',
                    'backgroundColor' => 'rgba(125, 96, 69, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Ručne prodaje',
                    'data' => $manualRevenue,
                    'borderColor' => '#c07a4a',
                    'backgroundColor' => 'rgba(192, 122, 74, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'callback' => 'function(v){ return v.toLocaleString("bs-BA") + " KM"; }',
                    ],
                ],
            ],
        ];
    }
}
