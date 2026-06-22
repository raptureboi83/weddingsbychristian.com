<?php

namespace App\Filament\Resources\PackageSections\Pages;

use App\Filament\Resources\PackageSections\PackagesSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPackagesSection extends EditRecord
{
    protected static string $resource = PackagesSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}