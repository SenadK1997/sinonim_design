<?php

namespace App\Filament\Resources\Collections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CollectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->label(''),
                TextColumn::make('name')->label('Naziv')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->color('gray'),
                TextColumn::make('products_count')->label('Proizvoda')->counts('products'),
                IconColumn::make('is_featured')->label('Istaknuta')->boolean(),
                TextColumn::make('published_at')->label('Objavljeno')->dateTime('d.m.Y')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
