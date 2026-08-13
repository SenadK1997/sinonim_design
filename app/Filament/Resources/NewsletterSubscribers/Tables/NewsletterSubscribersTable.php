<?php

namespace App\Filament\Resources\NewsletterSubscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')->label('E-mail')->searchable()->sortable(),
                TextColumn::make('locale')->label('Jezik')->badge(),
                TextColumn::make('created_at')->label('Prijavljen')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
