<?php

namespace App\Support;

class Money
{
    public static function format(float|int|null $amount, ?string $currency = null): string
    {
        $currency = $currency ?? config('app.currency', 'BAM');
        $amount = (float) ($amount ?? 0);

        $formatted = number_format($amount, 2, ',', '.');

        return match ($currency) {
            'BAM' => "{$formatted} KM",
            'EUR' => "€ {$formatted}",
            'USD' => "\$ {$formatted}",
            default => "{$formatted} {$currency}",
        };
    }
}
