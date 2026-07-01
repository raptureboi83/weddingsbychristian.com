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
                    ->description('Create reusable content blocks that can be shown in package-related sections of the website.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Block title')
                            ->helperText('The heading shown for this content block.')
                            ->placeholder('Frequently Asked Questions')
                            ->maxLength(255),

                        Select::make('block_type')
                            ->label('Block type')
                            ->helperText('Choose the kind of content block you want to create.')
                            ->required()
                            ->options([
                                'content' => 'Content (full width)',
                                'content-short' => 'Content (short span)',
                                'faq' => 'FAQ',
                                'note' => 'Note',
                                'cta' => 'Call to Action',
                            ])
                            ->default('content'),

                        Textarea::make('content')
                            ->label('Block content')
                            ->helperText('The main text shown inside this content block.')
                            ->placeholder('Add the full content for this block here.')
                            ->rows(8)
                            ->columnSpanFull(),

                        TextInput::make('cta_label')
                            ->label('Button text')
                            ->helperText('Optional. The text shown on a button inside this block.')
                            ->placeholder('Contact Me')
                            ->maxLength(255),

                        TextInput::make('cta_url')
                            ->label('Button link')
                            ->helperText('Optional. Where the button should send visitors.')
                            ->placeholder('/contact')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers appear first.')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Toggle::make('is_active')
                            ->label('Show this block on the website')
                            ->helperText('Turn this off to hide this block from the live site.')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}