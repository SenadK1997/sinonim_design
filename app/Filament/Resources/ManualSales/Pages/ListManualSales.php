<?php

namespace App\Filament\Resources\ManualSales\Pages;

use App\Filament\Resources\ManualSales\ManualSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManualSales extends ListRecords
{
    protected static string $resource = ManualSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
