<?php

namespace App\Filament\Resources\Discounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kod')->searchable()->weight('bold')->color('primary'),
                TextColumn::make('type')->label('Vrsta')->formatStateUsing(fn ($state) => $state === 'percentage' ? '%' : 'KM')->badge(),
                TextColumn::make('value')->label('Vrijednost'),
                TextColumn::make('used_count')->label('Iskorišten')->formatStateUsing(fn ($state, $record) => $state . ($record->usage_limit ? ' / ' . $record->usage_limit : '')),
                TextColumn::make('starts_at')->label('Od')->dateTime('d.m.Y'),
                TextColumn::make('ends_at')->label('Do')->dateTime('d.m.Y'),
                IconColumn::make('is_active')->label('Aktivan')->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
