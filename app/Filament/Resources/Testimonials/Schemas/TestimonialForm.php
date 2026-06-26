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
                    ->description('Add a client review that can be shown on the website.')
                    ->schema([
                        TextInput::make('couple_names')
                            ->label('Couple names')
                            ->helperText('The names shown with this testimonial.')
                            ->placeholder('Emily & Zander')
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('wedding_date')
                            ->label('Wedding date')
                            ->helperText('Optional. The wedding date connected to this testimonial.'),

                        Textarea::make('quote')
                            ->label('Testimonial quote')
                            ->helperText('The full review or client quote you want to display.')
                            ->placeholder('Christian captured our day so beautifully and gave us something we will treasure forever.')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),

                        TextInput::make('source_label')
                            ->label('Source label')
                            ->helperText('Optional. A short note showing where the review came from.')
                            ->placeholder('Google Review')
                            ->maxLength(255),

                        TextInput::make('source_url')
                            ->label('Source link')
                            ->helperText('Optional. A direct link to the original review.')
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers appear first.')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label('Show this testimonial on the website')
                            ->helperText('Turn this off to hide this testimonial from the live site.')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Feature this testimonial')
                            ->helperText('Turn this on to highlight this review in featured areas.')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}