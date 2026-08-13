<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('gallery')
                    ->collection('gallery')
                    ->conversion('thumb')
                    ->label('')
                    ->limit(1),
                TextColumn::make('name')->label('Naziv')->searchable()->sortable()->weight('semibold'),
                TextColumn::make('category.name')->label('Kategorija')->badge()->color('gray'),
                TextColumn::make('base_price')
                    ->label('Cijena')
                    ->formatStateUsing(fn ($state) => \App\Support\Money::format((float) $state))
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->label('Sniženje')
                    ->formatStateUsing(fn ($state) => $state ? \App\Support\Money::format((float) $state) : '—')
                    ->color('danger'),
                TextColumn::make('variants_sum_stock')
                    ->label('Zaliha')
                    ->sum('variants', 'stock')
                    ->badge()
                    ->color(fn ($state) => (int) $state > 0 ? 'success' : 'danger'),
                IconColumn::make('is_promoted')->label('Istaknut')->boolean(),
                IconColumn::make('is_made_to_order')->label('Po narudžbi')->boolean(),
                IconColumn::make('published_at')
                    ->label('Objavljen')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->published_at && $record->published_at->isPast()),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategorija')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_promoted')->label('Istaknuti'),
                TernaryFilter::make('is_made_to_order')->label('Po narudžbi'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
