<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id', 'name', 'description', 'care_instructions', 'slug', 'sku',
        'base_price', 'sale_price', 'is_promoted', 'is_made_to_order',
        'published_at', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_promoted' => 'boolean',
        'is_made_to_order' => 'boolean',
        'published_at' => 'datetime',
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class)->withPivot('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(400)->height(500)->nonQueued();
        $this->addMediaConversion('card')->width(700)->height(875)->nonQueued();
        $this->addMediaConversion('large')->width(1400)->height(1750)->nonQueued();
    }

    public function scopePublished($q)
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopePromoted($q)
    {
        return $q->where('is_promoted', true);
    }

    public function effectivePrice(): float
    {
        return (float) ($this->sale_price ?: $this->base_price);
    }

    public function isOnSale(): bool
    {
        return $this->sale_price !== null && (float) $this->sale_price < (float) $this->base_price;
    }

    public function totalStock(): int
    {
        return (int) $this->variants()->sum('stock');
    }

    public function isInStock(): bool
    {
        return $this->is_made_to_order || $this->totalStock() > 0;
    }

    public function primaryImageUrl(string $conversion = 'card'): ?string
    {
        $media = $this->getFirstMedia('gallery');
        return $media?->getUrl($conversion);
    }
}
