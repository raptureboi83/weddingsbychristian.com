<?php

namespace App\Filament\Resources\VendorCategories\Pages;

use App\Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\VendorCategories\VendorCategoryResource;

class CreateVendorCategory extends CreateRecord
{
    protected static string $resource = VendorCategoryResource::class;
}