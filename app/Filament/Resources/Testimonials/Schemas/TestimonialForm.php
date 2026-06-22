<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial Details')
                    ->schema([
                        TextInput::make('couple_names')
                            ->label('Couple Names')
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('wedding_date'),

                        Textarea::make('quote')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),

                        TextInput::make('source_label')
                            ->label('Source Label')
                            ->maxLength(255),

                        TextInput::make('source_url')
                            ->label('Source URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}