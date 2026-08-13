<?php

namespace App\Filament\Resources\Discounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('code')
                    ->label('Kod popusta')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->helperText('npr. SALE10, LJETO25')
                    ->afterStateHydrated(fn ($state, callable $set) => $set('code', strtoupper((string) $state)))
                    ->dehydrateStateUsing(fn ($state) => strtoupper((string) $state)),

                Select::make('type')
                    ->label('Vrsta popusta')
                    ->options([
                        'percentage' => 'Postotak (%)',
                        'fixed' => 'Fiksni iznos (KM)',
                    ])
                    ->default('percentage')
                    ->required()
                    ->native(false),

                TextInput::make('value')
                    ->label('Vrijednost')
                    ->numeric()
                    ->required()
                    ->step(0.01),

                TextInput::make('min_order_amount')
                    ->label('Minimalna vrijednost narudžbe (KM)')
                    ->numeric()
                    ->step(0.01)
                    ->helperText('Ostavi prazno ako nema minimuma.'),

                DateTimePicker::make('starts_at')->label('Vrijedi od'),
                DateTimePicker::make('ends_at')->label('Vrijedi do'),

                TextInput::make('usage_limit')
                    ->label('Maksimalno korištenja')
                    ->numeric()
                    ->helperText('Ostavi prazno za neograničeno.'),

                Toggle::make('is_active')->label('Aktivan')->default(true),
            ]),
        ]);
    }
}
