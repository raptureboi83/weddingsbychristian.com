<?php

namespace App\Filament\Resources\Testimonials\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Testimonials\TestimonialResource;
use Filament\Actions\DeleteAction;

class EditTestimonial extends EditRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
