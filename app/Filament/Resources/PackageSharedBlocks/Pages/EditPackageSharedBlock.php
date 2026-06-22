<?php

namespace App\Filament\Resources\PackageSharedBlocks\Pages;

use App\Filament\Resources\PackageSharedBlocks\PackageSharedBlockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPackageSharedBlock extends EditRecord
{
    protected static string $resource = PackageSharedBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}