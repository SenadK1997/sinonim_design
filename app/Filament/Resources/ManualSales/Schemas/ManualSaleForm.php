<?php

namespace App\Filament\Resources\ManualSales\Schemas;

use App\Models\ManualSale;
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
                DatePicker::make('sold_at')->label('Datum prodaje')->default(now())->required(),
                Select::make('channel')
                    ->label('Kanal')
                    ->options(ManualSale::channels())
                    ->required()
                    ->native(false),
                Select::make('product_id')
                    ->label('Proizvod (opcionalno)')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $product = \App\Models\Product::find($state);
                            if ($product) {
                                $set('product_name', $product->name);
                                $set('amount', $product->effectivePrice());
                            }
                        }
                    }),
                TextInput::make('product_name')->label('Naziv proizvoda')->required(),
                TextInput::make('quantity')->label('Količina')->numeric()->default(1)->required(),
                TextInput::make('amount')->label('Ukupan iznos (KM)')->numeric()->suffix('KM')->required(),
                TextInput::make('customer_name')->label('Kupac (opcionalno)'),
                Textarea::make('note')->label('Napomena')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
