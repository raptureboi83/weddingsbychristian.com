<?php

namespace App\Filament\Resources\PackageSections\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackagesSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Packages Page Content')
                    ->description('Edit the main text content that appears across the Packages page.')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small heading')
                            ->helperText('A short line shown above the main heading.')
                            ->placeholder('Packages')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main heading')
                            ->helperText('The main title shown at the top of the Packages page.')
                            ->placeholder('Wedding film collections crafted with care')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Short description')
                            ->helperText('A short paragraph shown under the main heading.')
                            ->placeholder('Introduce your package offerings with a short summary here.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Textarea::make('intro')
                            ->label('Intro text')
                            ->helperText('A longer introduction shown before the package details.')
                            ->placeholder('Use this area to explain your approach, package philosophy, or how couples can choose the right fit.')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('bottom_heading')
                            ->label('Bottom heading')
                            ->helperText('A heading shown near the bottom of the page.')
                            ->placeholder('Still have questions?')
                            ->maxLength(255),

                        Textarea::make('bottom_description')
                            ->label('Bottom description')
                            ->helperText('Supporting text shown under the bottom heading.')
                            ->placeholder('Add a short closing message that invites couples to get in touch.')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('cta_label')
                            ->label('Button text')
                            ->helperText('The text shown on the call-to-action button.')
                            ->placeholder('Get in Touch')
                            ->maxLength(255),

                        TextInput::make('cta_url')
                            ->label('Button link')
                            ->helperText('Where the button should send visitors, such as /contact.')
                            ->placeholder('/contact')
                            ->maxLength(255),

                        Toggle::make('is_published')
                            ->label('Show this section on the website')
                            ->helperText('Turn this off to hide this section from the live site.')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}