<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ime')->searchable()->sortable()->weight('semibold'),
                TextColumn::make('phone')->label('Telefon')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('city')->label('Grad')->searchable(),
                TextColumn::make('orders_count')->label('Narudžbi')->counts('orders'),
                TextColumn::make('created_at')->label('Registrovan')->dateTime('d.m.Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
