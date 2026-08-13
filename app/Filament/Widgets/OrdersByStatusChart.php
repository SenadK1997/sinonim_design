<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChart extends ChartWidget
{
    protected ?string $heading = 'Narudžbe po statusu';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $counts = [
            Order::STATUS_PENDING => Order::where('status', Order::STATUS_PENDING)->count(),
            Order::STATUS_CONFIRMED => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            Order::STATUS_SHIPPED => Order::where('status', Order::STATUS_SHIPPED)->count(),
            Order::STATUS_COMPLETED => Order::where('status', Order::STATUS_COMPLETED)->count(),
            Order::STATUS_CANCELLED => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return [
            'datasets' => [[
                'label' => 'Narudžbe',
                'data' => array_values($counts),
                'backgroundColor' => ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'],
                'borderWidth' => 0,
            ]],
            'labels' => array_map(fn ($k) => Order::statuses()[$k], array_keys($counts)),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
