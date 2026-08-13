<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Naziv kategorije')
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
                ->rows(3)
                ->columnSpanFull(),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('URL segment, npr. "haljine". Automatski se generiše iz naziva.'),

            Select::make('parent_id')
                ->label('Nadređena kategorija')
                ->relationship('parent', 'name')
                ->searchable()
                ->preload()
                ->nullable(),

            TextInput::make('sort_order')
                ->label('Redoslijed')
                ->numeric()
                ->default(0),

            Toggle::make('is_published')
                ->label('Objavljena')
                ->default(true),
        ]);
    }
}
