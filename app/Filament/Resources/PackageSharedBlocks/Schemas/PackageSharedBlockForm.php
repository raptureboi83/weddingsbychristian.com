<?php

namespace App\Filament\Resources\PackageSharedBlocks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PackageSharedBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Shared Block Details')
                    ->schema([
                        TextInput::make('title')
                            ->maxLength(255),

                        Select::make('block_type')
                            ->required()
                            ->options([
                                'content' => 'Content',
                                'faq' => 'FAQ',
                                'note' => 'Note',
                                'cta' => 'Call to Action',
                            ])
                            ->default('content'),

                        Textarea::make('content')
                            ->rows(8)
                            ->columnSpanFull(),

                        TextInput::make('cta_label')
                            ->label('CTA Label')
                            ->maxLength(255),

                        TextInput::make('cta_url')
                            ->label('CTA URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}