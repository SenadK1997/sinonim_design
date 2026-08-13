<?php

namespace App\Filament\Resources\ManualSales;

use App\Filament\Resources\ManualSales\Pages\CreateManualSale;
use App\Filament\Resources\ManualSales\Pages\EditManualSale;
use App\Filament\Resources\ManualSales\Pages\ListManualSales;
use App\Filament\Resources\ManualSales\Schemas\ManualSaleForm;
use App\Filament\Resources\ManualSales\Tables\ManualSalesTable;
use App\Models\ManualSale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManualSaleResource extends Resource
{
    protected static ?string $model = ManualSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Ručne prodaje';

    protected static ?string $modelLabel = 'Ručna prodaja';

    protected static ?string $pluralModelLabel = 'Ručne prodaje';

    protected static string|\UnitEnum|null $navigationGroup = 'Prodaja';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ManualSaleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManualSalesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManualSales::route('/'),
            'create' => CreateManualSale::route('/create'),
            'edit' => EditManualSale::route('/{record}/edit'),
        ];
    }
}
