<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kupac')->columns(2)->schema([
                TextInput::make('name')->label('Ime i prezime')->required(),
                TextInput::make('phone')->label('Telefon')->tel(),
                TextInput::make('email')->label('E-mail')->email(),
                TextInput::make('city')->label('Grad'),
                TextInput::make('address')->label('Adresa')->columnSpanFull(),
                TextInput::make('postal_code')->label('Poštanski broj'),
                TextInput::make('country')->label('Država')->default('BA'),
                Textarea::make('notes')->label('Napomena')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }
}
