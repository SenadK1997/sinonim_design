<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Narudžba')->columns(3)->schema([
                TextInput::make('order_number')
                    ->label('Broj narudžbe')
                    ->default(fn () => Order::generateOrderNumber())
                    ->disabled()
                    ->dehydrated(),
                Select::make('status')
                    ->label('Status')
                    ->options(Order::statuses())
                    ->default(Order::STATUS_PENDING)
                    ->required()
                    ->native(false),
                Select::make('source')
                    ->label('Izvor narudžbe')
                    ->options(Order::sources())
                    ->default(Order::SOURCE_WEB)
                    ->required()
                    ->native(false),
            ]),

            Section::make('Kupac')->columns(2)->schema([
                TextInput::make('customer_name')->label('Ime i prezime')->required(),
                TextInput::make('customer_phone')->label('Telefon')->tel()->required(),
                TextInput::make('customer_email')->label('E-mail')->email(),
                TextInput::make('shipping_city')->label('Grad')->required(),
                TextInput::make('shipping_address')->label('Adresa')->required()->columnSpanFull(),
                TextInput::make('shipping_postal_code')->label('Poštanski broj'),
                TextInput::make('shipping_country')->label('Država')->default('BA'),
            ]),

            Section::make('Stavke narudžbe')->schema([
                Repeater::make('items')
                    ->label('')
                    ->relationship()
                    ->schema([
                        TextInput::make('product_name')->label('Naziv proizvoda')->required(),
                        TextInput::make('size')->label('Veličina'),
                        TextInput::make('color')->label('Boja'),
                        TextInput::make('quantity')->label('Količina')->numeric()->default(1)->required(),
                        TextInput::make('unit_price')->label('Cijena po komadu')->numeric()->suffix('KM')->required(),
                        TextInput::make('line_total')->label('Ukupno')->numeric()->suffix('KM')->required(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Dodaj stavku')
                    ->defaultItems(1),
            ]),

            Section::make('Iznosi')->columns(3)->schema([
                TextInput::make('subtotal')->label('Međuzbir')->numeric()->suffix('KM')->required(),
                TextInput::make('shipping_cost')->label('Dostava')->numeric()->suffix('KM')->default(0),
                TextInput::make('discount_amount')->label('Popust')->numeric()->suffix('KM')->default(0),
                TextInput::make('discount_code')->label('Kod popusta'),
                TextInput::make('total')->label('UKUPNO')->numeric()->suffix('KM')->required(),
                Select::make('payment_method')
                    ->label('Način plaćanja')
                    ->options(['cod' => 'Pouzeće'])
                    ->default('cod'),
            ]),

            Section::make('Napomene')->schema([
                Textarea::make('notes')->label('Napomena kupca')->rows(2),
                Textarea::make('admin_notes')->label('Interna napomena (nije vidljiva kupcu)')->rows(2),
            ]),
        ]);
    }
}
