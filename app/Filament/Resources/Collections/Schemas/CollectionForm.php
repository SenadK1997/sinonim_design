<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Osnovni podaci')->columns(2)->schema([
                TextInput::make('name')
                    ->label('Naziv kolekcije')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (blank($get('slug')) && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Opis')
                    ->rows(4)
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                DateTimePicker::make('published_at')
                    ->label('Datum objave')
                    ->helperText('Ostavi prazno za skicu.'),

                Toggle::make('is_featured')
                    ->label('Istaknuta na naslovnoj'),
            ]),

            Section::make('Naslovna slika')->schema([
                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('')
                    ->collection('cover')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
