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
                    ->schema([
                        TextInput::make('eyebrow')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),

                        Textarea::make('intro')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('bottom_heading')
                            ->maxLength(255),

                        Textarea::make('bottom_description')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('cta_label')
                            ->maxLength(255),

                        TextInput::make('cta_url')
                            ->maxLength(255),

                        Toggle::make('is_published')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}