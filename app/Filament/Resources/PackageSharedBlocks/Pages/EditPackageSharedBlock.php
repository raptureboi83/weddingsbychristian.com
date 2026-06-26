<?php

namespace App\Filament\Resources\PackageSharedBlocks\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PackageSharedBlocks\PackageSharedBlockResource;
use Filament\Actions\DeleteAction;

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