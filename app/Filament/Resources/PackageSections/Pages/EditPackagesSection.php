<?php

namespace App\Filament\Resources\PackageSections\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PackageSections\PackagesSectionResource;
use Filament\Actions\DeleteAction;

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