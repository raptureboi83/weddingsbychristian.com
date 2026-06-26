<?php

namespace App\Filament\Resources\PackageItems\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PackageItems\PackageItemResource;
use Filament\Actions\DeleteAction;

class EditPackageItem extends EditRecord
{
    protected static string $resource = PackageItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}