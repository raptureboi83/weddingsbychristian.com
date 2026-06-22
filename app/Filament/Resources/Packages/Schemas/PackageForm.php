<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Package Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('short_description')
                        ->maxLength(255),

                    Textarea::make('description')
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Pricing')
                ->schema([
                    TextInput::make('price_label')
                        ->label('Price Label')
                        ->placeholder('Starting at')
                        ->maxLength(255),

                    TextInput::make('starting_price')
                        ->numeric()
                        ->prefix('$')
                        ->step('0.01'),

                    TextInput::make('duration_label')
                        ->label('Duration Label')
                        ->placeholder('8 hours coverage')
                        ->maxLength(255),
                ])
                ->columns(3),

            Section::make('Deliverables')
                ->schema([
                    TagsInput::make('deliverables')
                        ->placeholder('Add a deliverable and press enter')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),

            Section::make('Package Items')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->schema([
                            TextInput::make('label')
                                ->required()
                                ->maxLength(255),

                            Textarea::make('description')
                                ->rows(3),

                            TextInput::make('sort_order')
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_highlighted')
                                ->label('Highlighted Item')
                                ->default(false),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->columns(2)
                        ->columnSpanFull(),
                ]),

            Section::make('Visibility')
                ->schema([
                    Toggle::make('is_featured')
                        ->label('Featured')
                        ->default(false),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),
        ]);
    }
}