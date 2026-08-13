<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'city',
        'postal_code', 'country', 'notes', 'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function totalSpent(): float
    {
        return (float) $this->orders()->whereIn('status', ['confirmed', 'shipped', 'completed'])->sum('total');
    }
}
