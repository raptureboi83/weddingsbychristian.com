<?php

namespace App\Filament\Resources\PackageSections;

use App\Filament\Resources\PackageSections\Pages\CreatePackagesSection;
use App\Filament\Resources\PackageSections\Pages\EditPackagesSection;
use App\Filament\Resources\PackageSections\Pages\ListPackagesSections;
use App\Filament\Resources\PackageSections\Schemas\PackagesSectionForm;
use App\Filament\Resources\PackageSections\Tables\PackagesSectionsTable;
use App\Models\PackagesSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PackagesSectionResource extends Resource
{
    protected static ?string $model = PackagesSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Packages Section';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 32;

    protected static ?string $recordTitleAttribute = 'title';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PackagesSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackageSectionsTable::configure($table);
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
            'index' => ListPackagesSections::route('/'),
            'create' => CreatePackagesSection::route('/create'),
            'edit' => EditPackagesSection::route('/{record}/edit'),
        ];
    }
}