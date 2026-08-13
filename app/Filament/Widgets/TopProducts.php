<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use App\Support\Money;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class TopProducts extends Widget
{
    protected string $view = 'filament.widgets.top-products';

    protected int|string|array $columnSpan = 'full';

    public function getTopProducts(): array
    {
        $rows = OrderItem::query()
            ->select([
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(line_total) as total_revenue'),
            ])
            ->whereHas('order', fn ($q) => $q
                ->whereIn('status', ['confirmed', 'shipped', 'completed'])
                ->where('created_at', '>=', now()->subDays(30)))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        // Preload products so we can render thumbnails
        $productIds = $rows->pluck('product_id')->filter()->unique()->values();
        $products = Product::with('media')->find($productIds)->keyBy('id');

        $maxQty = (int) $rows->max('total_qty') ?: 1;

        return $rows->map(fn ($row) => [
            'id' => $row->product_id,
            'name' => $row->product_name,
            'qty' => (int) $row->total_qty,
            'revenue' => Money::format((float) $row->total_revenue),
            'thumb' => $row->product_id ? ($products->get($row->product_id)?->primaryImageUrl('thumb')) : null,
            'bar_percent' => (int) round(((int) $row->total_qty / $maxQty) * 100),
        ])->toArray();
    }
}
