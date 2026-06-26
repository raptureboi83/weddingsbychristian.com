<?php

namespace App\Filament\Resources\VendorCategories\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VendorCategories\VendorCategoryResource;
use Filament\Actions\DeleteAction;

class EditVendorCategory extends EditRecord
{
    protected static string $resource = VendorCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}