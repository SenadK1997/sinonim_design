<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('Broj')->searchable()->weight('semibold'),
                TextColumn::make('created_at')->label('Datum')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('customer_name')->label('Kupac')->searchable(),
                TextColumn::make('shipping_city')->label('Grad'),
                TextColumn::make('items_count')->label('Stavki')->counts('items'),
                TextColumn::make('total')
                    ->label('Ukupno')
                    ->formatStateUsing(fn ($state) => \App\Support\Money::format((float) $state))
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Izvor')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Order::sources()[$state] ?? $state),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Order::statuses()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_CONFIRMED => 'info',
                        Order::STATUS_SHIPPED => 'primary',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')->options(Order::statuses()),
                SelectFilter::make('source')->options(Order::sources()),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
