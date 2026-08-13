<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Support\Money;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class TopProducts extends Widget
{
    protected string $view = 'filament.widgets.top-products';

    protected int|string|array $columnSpan = 'full';

    public function getTopProducts(): array
    {
        return OrderItem::query()
            ->select([
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(line_total) as total_revenue'),
            ])
            ->whereHas('order', fn ($q) => $q
                ->whereIn('status', ['confirmed', 'shipped', 'completed'])
                ->where('created_at', '>=', now()->subDays(30)))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->product_name,
                'qty' => (int) $row->total_qty,
                'revenue' => Money::format((float) $row->total_revenue),
            ])
            ->toArray();
    }
}
