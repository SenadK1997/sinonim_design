<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Support\Money;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopProducts extends TableWidget
{
    protected static ?string $heading = 'Najprodavaniji proizvodi (zadnjih 30 dana)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => OrderItem::query()
                ->selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(line_total) as total_revenue')
                ->whereHas('order', fn ($q) => $q->whereIn('status', ['confirmed', 'shipped', 'completed'])
                    ->where('created_at', '>=', now()->subDays(30)))
                ->groupBy('product_id', 'product_name')
                ->orderByDesc('total_qty')
            )
            ->columns([
                TextColumn::make('product_name')->label('Proizvod')->weight('semibold'),
                TextColumn::make('total_qty')->label('Prodato kom')->alignCenter(),
                TextColumn::make('total_revenue')
                    ->label('Prihod')
                    ->formatStateUsing(fn ($state) => Money::format((float) $state))
                    ->alignEnd(),
            ])
            ->emptyStateHeading('Još nema podataka o prodaji')
            ->emptyStateDescription('Prodaja iz ovog mjeseca će se prikazati ovdje.')
            ->paginated(false);
    }
}
