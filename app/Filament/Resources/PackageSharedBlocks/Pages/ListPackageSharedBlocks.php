<?php

namespace App\Filament\Resources\PackageSharedBlocks\Pages;

use App\Filament\Resources\PackageSharedBlocks\PackageSharedBlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPackageSharedBlocks extends ListRecords
{
    protected static string $resource = PackageSharedBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}