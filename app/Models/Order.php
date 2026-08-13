<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const SOURCE_WEB = 'web';
    public const SOURCE_INSTAGRAM = 'instagram';
    public const SOURCE_WHATSAPP = 'whatsapp';
    public const SOURCE_VIBER = 'viber';
    public const SOURCE_OTHER = 'other';

    protected $fillable = [
        'order_number', 'customer_id', 'status', 'source',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_postal_code', 'shipping_country',
        'subtotal', 'shipping_cost', 'discount_amount', 'discount_code',
        'total', 'currency', 'notes', 'admin_notes', 'payment_method',
        'confirmed_at', 'shipped_at', 'completed_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public static function generateOrderNumber(): string
    {
        return 'SD-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Na čekanju',
            self::STATUS_CONFIRMED => 'Potvrđena',
            self::STATUS_SHIPPED => 'Poslana',
            self::STATUS_COMPLETED => 'Završena',
            self::STATUS_CANCELLED => 'Otkazana',
        ];
    }

    public static function sources(): array
    {
        return [
            self::SOURCE_WEB => 'Web',
            self::SOURCE_INSTAGRAM => 'Instagram',
            self::SOURCE_WHATSAPP => 'WhatsApp',
            self::SOURCE_VIBER => 'Viber',
            self::SOURCE_OTHER => 'Ostalo',
        ];
    }
}
