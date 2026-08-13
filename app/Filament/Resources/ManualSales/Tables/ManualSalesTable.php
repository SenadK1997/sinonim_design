<?php

namespace App\Filament\Resources\ManualSales\Tables;

use App\Models\ManualSale;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ManualSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sold_at')->label('Datum')->date('d.m.Y')->sortable(),
                TextColumn::make('product_name')->label('Proizvod')->searchable(),
                TextColumn::make('quantity')->label('Kom')->alignCenter(),
                TextColumn::make('amount')
                    ->label('Iznos')
                    ->formatStateUsing(fn ($state) => \App\Support\Money::format((float) $state))
                    ->sortable(),
                TextColumn::make('channel')
                    ->label('Kanal')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ManualSale::channels()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'instagram' => 'danger',
                        'whatsapp' => 'success',
                        'viber' => 'purple',
                        'in_person' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('customer_name')->label('Kupac')->searchable(),
            ])
            ->filters([
                SelectFilter::make('channel')->options(ManualSale::channels()),
            ])
            ->defaultSort('sold_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
