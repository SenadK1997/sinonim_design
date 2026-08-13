<?php

namespace App\Filament\Resources\ManualSales\Schemas;

use App\Models\Customer;
use App\Models\ManualSale;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManualSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Prodaja')->columns(2)->schema([
                DatePicker::make('sold_at')
                    ->label('Datum prodaje')
                    ->default(now())
                    ->required()
                    ->native(false)
                    ->displayFormat('d.m.Y'),

                Select::make('channel')
                    ->label('Kanal prodaje')
                    ->options(ManualSale::channels())
                    ->required()
                    ->native(false),

                Select::make('product_id')
                    ->label('Proizvod iz kataloga')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $product = Product::find($state);
                            if ($product) {
                                $set('product_name', $product->name);
                                if (! $get('amount')) {
                                    $set('amount', $product->effectivePrice());
                                }
                            }
                        }
                    })
                    ->helperText('Ostavi prazno ako proizvod nije iz kataloga.'),

                TextInput::make('product_name')
                    ->label('Naziv proizvoda')
                    ->required()
                    ->helperText('Automatski se popuni ako odabereš proizvod iznad.'),

                TextInput::make('quantity')
                    ->label('Količina')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),

                TextInput::make('amount')
                    ->label('Ukupan iznos')
                    ->numeric()
                    ->suffix('KM')
                    ->step(0.01)
                    ->required(),
            ]),

            Section::make('Kupac (opcionalno)')
                ->description('Odaberi postojećeg kupca ili unesi novo ime — možeš ostaviti prazno.')
                ->columns(2)
                ->schema([
                    Select::make('customer_id')
                        ->label('Postojeći kupac')
                        ->options(fn () => Customer::orderBy('name')->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $customer = Customer::find($state);
                                if ($customer) {
                                    $set('customer_name', $customer->name);
                                }
                            }
                        })
                        ->createOptionForm([
                            TextInput::make('name')->label('Ime i prezime')->required(),
                            TextInput::make('phone')->label('Telefon')->tel(),
                            TextInput::make('email')->label('E-mail')->email(),
                            TextInput::make('city')->label('Grad'),
                        ])
                        ->createOptionUsing(fn (array $data) => Customer::create($data)->id)
                        ->createOptionAction(fn ($action) => $action->modalHeading('Novi kupac'))
                        ->helperText('Pretraži postojeće ili klikni + za dodavanje novog.'),

                    TextInput::make('customer_name')
                        ->label('Ili samo upiši ime')
                        ->helperText('Ako se ne registruje kao stalni kupac.'),

                    Textarea::make('note')
                        ->label('Napomena')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
