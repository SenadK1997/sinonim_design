<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
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
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),

                    Action::make('confirm')
                        ->label('Označi kao potvrđenu')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->visible(fn (Order $r) => $r->status === Order::STATUS_PENDING)
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $record->update(['status' => Order::STATUS_CONFIRMED, 'confirmed_at' => now()]);
                            Notification::make()->title('Narudžba potvrđena')->success()->send();
                        }),

                    Action::make('ship')
                        ->label('Označi kao poslanu')
                        ->icon('heroicon-o-truck')
                        ->color('primary')
                        ->visible(fn (Order $r) => in_array($r->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED]))
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $record->update(['status' => Order::STATUS_SHIPPED, 'shipped_at' => now(), 'confirmed_at' => $record->confirmed_at ?? now()]);
                            Notification::make()->title('Narudžba poslana')->success()->send();
                        }),

                    Action::make('complete')
                        ->label('Označi kao završenu')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn (Order $r) => in_array($r->status, [Order::STATUS_SHIPPED, Order::STATUS_CONFIRMED]))
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $record->update(['status' => Order::STATUS_COMPLETED, 'completed_at' => now()]);
                            Notification::make()->title('Narudžba završena')->success()->send();
                        }),

                    Action::make('cancel')
                        ->label('Otkaži narudžbu')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Order $r) => ! in_array($r->status, [Order::STATUS_CANCELLED, Order::STATUS_COMPLETED]))
                        ->requiresConfirmation()
                        ->modalDescription('Sigurno želiš otkazati ovu narudžbu?')
                        ->action(function (Order $record) {
                            $record->update(['status' => Order::STATUS_CANCELLED, 'cancelled_at' => now()]);
                            Notification::make()->title('Narudžba otkazana')->warning()->send();
                        }),
                ]),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
