<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Naziv')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->color('gray'),
                TextColumn::make('parent.name')->label('Nadređena')->badge(),
                TextColumn::make('products_count')->label('Proizvoda')->counts('products'),
                IconColumn::make('is_published')->label('Objavljena')->boolean(),
                TextColumn::make('sort_order')->label('#')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
