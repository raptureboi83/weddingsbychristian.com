<?php

namespace App\Filament\Resources\Testimonials\Pages;

use App\Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Testimonials\TestimonialResource;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;
}