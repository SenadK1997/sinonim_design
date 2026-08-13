<?php

namespace App\Filament\Widgets;

use App\Models\ManualSale;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesByChannelChart extends ChartWidget
{
    protected ?string $heading = 'Prodaja po kanalu (zadnjih 30 dana)';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $since = now()->subDays(30);

        $webTotal = (float) Order::query()
            ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])
            ->where('created_at', '>=', $since)
            ->sum('total');

        $channelData = ManualSale::query()
            ->where('sold_at', '>=', $since)
            ->selectRaw('channel, SUM(amount) as total')
            ->groupBy('channel')
            ->pluck('total', 'channel')
            ->toArray();

        $labels = ['Web'];
        $data = [$webTotal];
        $colors = ['#7d6045'];

        $channelColors = [
            'instagram' => '#e1306c',
            'whatsapp' => '#25d366',
            'viber' => '#7360f2',
            'in_person' => '#3b82f6',
            'other' => '#9ca3af',
        ];

        foreach (ManualSale::channels() as $key => $label) {
            $labels[] = $label;
            $data[] = (float) ($channelData[$key] ?? 0);
            $colors[] = $channelColors[$key] ?? '#9ca3af';
        }

        return [
            'datasets' => [[
                'label' => 'KM',
                'data' => $data,
                'backgroundColor' => $colors,
                'borderWidth' => 0,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
