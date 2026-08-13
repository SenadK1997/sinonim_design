<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'size', 'color', 'color_hex', 'sku', 'stock', 'price_override',
    ];

    protected $casts = [
        'stock' => 'integer',
        'price_override' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function price(): float
    {
        return (float) ($this->price_override ?: $this->product?->effectivePrice() ?: 0);
    }

    public function label(): string
    {
        return trim(implode(' / ', array_filter([$this->size, $this->color])));
    }
}
