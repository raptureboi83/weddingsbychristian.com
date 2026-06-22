<?php

namespace App\Filament\Resources\PackageSections\Pages;

use App\Filament\Resources\PackageSections\PackagesSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPackagesSections extends ListRecords
{
    protected static string $resource = PackagesSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}