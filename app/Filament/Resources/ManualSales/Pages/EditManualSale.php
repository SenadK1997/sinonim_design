<?php

namespace App\Filament\Resources\ManualSales\Pages;

use App\Filament\Resources\ManualSales\ManualSaleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManualSale extends EditRecord
{
    protected static string $resource = ManualSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
