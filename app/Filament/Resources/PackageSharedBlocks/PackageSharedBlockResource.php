<?php

namespace App\Filament\Resources\PackageSharedBlocks;

use App\Filament\Resources\PackageSharedBlocks\Pages\CreatePackageSharedBlock;
use App\Filament\Resources\PackageSharedBlocks\Pages\EditPackageSharedBlock;
use App\Filament\Resources\PackageSharedBlocks\Pages\ListPackageSharedBlocks;
use App\Filament\Resources\PackageSharedBlocks\Schemas\PackageSharedBlockForm;
use App\Filament\Resources\PackageSharedBlocks\Tables\PackageSharedBlocksTable;
use App\Models\PackageSharedBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PackageSharedBlockResource extends Resource
{
    protected static ?string $model = PackageSharedBlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static ?string $navigationLabel = 'Package Shared Blocks';

    protected static ?string $modelLabel = 'Package Shared Block';

    protected static ?string $pluralModelLabel = 'Package Shared Blocks';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 25;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PackageSharedBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackageSharedBlocksTable::configure($table);
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
            'index' => ListPackageSharedBlocks::route('/'),
            'create' => CreatePackageSharedBlock::route('/create'),
            'edit' => EditPackageSharedBlock::route('/{record}/edit'),
        ];
    }
}