<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    public function getSubheading(): ?string
    {
        return 'Ovdje su svi kupci koji su ikada nešto kupili — bilo preko sajta (web narudžba) ili ručno dodani (Instagram, WhatsApp, lično). Kupci se automatski kreiraju kada primiš prvu narudžbu od nekoga, ili ih možeš dodati ručno klikom na "Novi kupac".';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Novi kupac'),
        ];
    }
}
