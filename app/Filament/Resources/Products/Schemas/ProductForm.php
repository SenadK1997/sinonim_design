<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Osnovno')->schema([
                    Section::make()->columns(2)->schema([
                        TextInput::make('name')
                            ->label('Naziv proizvoda')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (blank($get('slug')) && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->helperText('Interni kod proizvoda (opcionalno).'),

                        Select::make('category_id')
                            ->label('Kategorija')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('collections')
                            ->label('Kolekcije')
                            ->relationship('collections', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),

                        Textarea::make('description')
                            ->label('Opis')
                            ->rows(6)
                            ->columnSpanFull(),

                        Textarea::make('care_instructions')
                            ->label('Upute za održavanje')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                ]),

                Tab::make('Cijena i objava')->schema([
                    Section::make()->columns(2)->schema([
                        TextInput::make('base_price')
                            ->label('Cijena')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('KM')
                            ->step(0.01),

                        TextInput::make('sale_price')
                            ->label('Snižena cijena')
                            ->numeric()
                            ->suffix('KM')
                            ->step(0.01)
                            ->helperText('Ostavi prazno ako proizvod nije na sniženju.'),

                        Toggle::make('is_promoted')
                            ->label('Prikaži na naslovnoj')
                            ->helperText('Proizvod će biti istaknut na naslovnoj stranici.'),

                        Toggle::make('is_made_to_order')
                            ->label('Izrada po narudžbi')
                            ->helperText('Označi ako se proizvod pravi tek nakon narudžbe.'),

                        DateTimePicker::make('published_at')
                            ->label('Objavljen')
                            ->default(now())
                            ->helperText('Ostavi prazno da sakriješ proizvod (skica).')
                            ->columnSpanFull(),
                    ]),
                ]),

                Tab::make('Varijante (veličina/boja)')->schema([
                    Section::make()->schema([
                        Repeater::make('variants')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('size')->label('Veličina')->placeholder('XS, S, M, L, XL, ...'),
                                TextInput::make('color')->label('Boja')->placeholder('crna, bijela, ...'),
                                TextInput::make('color_hex')->label('Hex')->placeholder('#000000')->maxLength(7),
                                TextInput::make('stock')->label('Zaliha')->numeric()->default(1)->required(),
                                TextInput::make('sku')->label('SKU'),
                                TextInput::make('price_override')
                                    ->label('Cijena (override)')
                                    ->numeric()
                                    ->suffix('KM')
                                    ->helperText('Prepiši osnovnu cijenu za ovu varijantu.'),
                            ])
                            ->columns(3)
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => trim(implode(' / ', array_filter([$state['size'] ?? null, $state['color'] ?? null]))) ?: 'Nova varijanta')
                            ->addActionLabel('Dodaj varijantu')
                            ->defaultItems(0)
                            ->reorderable(),
                    ]),
                ]),

                Tab::make('Galerija slika')->schema([
                    Section::make()
                        ->description('Upload slika proizvoda. Prva slika je glavna (prikazuje se u prodavnici i na kartici).')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->label('Slike')
                                ->collection('gallery')
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->image()
                                ->imageEditor()
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('4:5')
                                ->downloadable()
                                ->openable()
                                ->deletable()
                                ->panelLayout('grid')
                                ->maxSize(8192)
                                ->maxFiles(15)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->columnSpanFull()
                                ->helperText('💡 Klikni na kanticu (🗑) na slici da je obrišeš. Prevuci slike za promjenu redoslijeda — prva slika je glavna. Max 8 MB po slici, do 15 slika.'),
                        ]),
                ]),

                Tab::make('SEO (opcionalno)')->schema([
                    Section::make()
                        ->description(
                            'Ova polja NISU obavezna. Ako ih ostaviš prazna, koristi se naziv i opis proizvoda automatski.

Popuni samo ako želiš POSEBAN tekst kada se ovaj proizvod dijeli na Google, Facebook ili Instagram (npr. kraći, privlačniji naslov ili opis specifičan za pretragu).

Preporuka: preskoči ovo — mi ćemo napraviti globalni SEO za sajt.'
                        )
                        ->schema([
                            TextInput::make('meta_title')
                                ->label('SEO naslov (za Google i Facebook)')
                                ->maxLength(70)
                                ->helperText('Prikazuje se u pretragama i pri dijeljenju. Idealno 50-60 znakova.'),
                            Textarea::make('meta_description')
                                ->label('SEO opis')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Kratak opis koji Google pokaže ispod naslova u pretrazi. Idealno 120-155 znakova.'),
                        ]),
                ]),
            ])->columnSpanFull(),
        ]);
    }
}
