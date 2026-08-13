<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nova narudžba'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Sve')
                ->badge(fn (): int => Order::count()),

            'pending' => Tab::make('Nove')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_PENDING))
                ->badge(fn (): int => Order::where('status', Order::STATUS_PENDING)->count())
                ->badgeColor('warning'),

            'confirmed' => Tab::make('Potvrđene')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_CONFIRMED))
                ->badge(fn (): int => Order::where('status', Order::STATUS_CONFIRMED)->count())
                ->badgeColor('info'),

            'shipped' => Tab::make('Poslane')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_SHIPPED))
                ->badge(fn (): int => Order::where('status', Order::STATUS_SHIPPED)->count())
                ->badgeColor('primary'),

            'completed' => Tab::make('Završene')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_COMPLETED))
                ->badge(fn (): int => Order::where('status', Order::STATUS_COMPLETED)->count())
                ->badgeColor('success'),

            'cancelled' => Tab::make('Otkazane')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_CANCELLED))
                ->badge(fn (): int => Order::where('status', Order::STATUS_CANCELLED)->count())
                ->badgeColor('danger'),
        ];
    }
}
