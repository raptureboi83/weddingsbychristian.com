<?php

namespace App\Filament\Resources\Packages\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Packages\PackageResource;
use Filament\Actions\DeleteAction;

class EditPackage extends EditRecord
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}