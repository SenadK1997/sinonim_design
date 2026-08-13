<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualSale extends Model
{
    protected $fillable = [
        'sold_at', 'product_id', 'product_name',
        'quantity', 'amount', 'channel', 'customer_name', 'note',
    ];

    protected $casts = [
        'sold_at' => 'date',
        'quantity' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function channels(): array
    {
        return [
            'instagram' => 'Instagram',
            'whatsapp' => 'WhatsApp',
            'viber' => 'Viber',
            'in_person' => 'Lično',
            'other' => 'Ostalo',
        ];
    }
}
