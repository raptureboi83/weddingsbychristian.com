<?php

namespace App\Filament\Resources\VendorCategories;

use App\Filament\Resources\VendorCategories\Pages\CreateVendorCategory;
use App\Filament\Resources\VendorCategories\Pages\EditVendorCategory;
use App\Filament\Resources\VendorCategories\Pages\ListVendorCategories;
use App\Filament\Resources\VendorCategories\Schemas\VendorCategoryForm;
use App\Filament\Resources\VendorCategories\Tables\VendorCategoriesTable;
use App\Models\VendorCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorCategoryResource extends Resource
{
    protected static ?string $model = VendorCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Vendor Categories';

    protected static ?string $modelLabel = 'Vendor Category';

    protected static ?string $pluralModelLabel = 'Vendor Categories';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 34;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return VendorCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorCategories::route('/'),
            'create' => CreateVendorCategory::route('/create'),
            'edit' => EditVendorCategory::route('/{record}/edit'),
        ];
    }
}