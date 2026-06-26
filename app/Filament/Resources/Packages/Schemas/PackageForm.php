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
        return $schema
            ->columns(2)
            ->components([
            Section::make('Package Details')
                ->description('Add the core information for a package shown on the Packages page.')
                ->schema([
                    TextInput::make('name')
                        ->label('Package name')
                        ->helperText('The name visitors will see for this package.')
                        ->placeholder('Premium Collection')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),

                    TextInput::make('slug')
                        ->label('URL name')
                        ->helperText('Used in the page link. This is usually filled automatically from the package name.')
                        ->placeholder('premium-collection')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('short_description')
                        ->label('Short description')
                        ->helperText('A short summary shown near the package name.')
                        ->placeholder('A cinematic package with full-day coverage.')
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Full description')
                        ->helperText('A longer description that explains the package in more detail.')
                        ->placeholder('Perfect for couples who want a fuller story of the day, from prep through the major reception moments.')
                        ->rows(3),
                ])
                ->columns(2),

            Section::make('Pricing')
                ->description('Set the price text and key pricing details shown with this package.')
                ->schema([
                    TextInput::make('price_label')
                        ->label('Price label')
                        ->helperText('Short text shown before the price.')
                        ->placeholder('Starting at')
                        ->maxLength(255),

                    TextInput::make('starting_price')
                        ->label('Starting price')
                        ->helperText('Enter the package starting price.')
                        ->numeric()
                        ->prefix('$')
                        ->step('0.01'),

                    TextInput::make('duration_label')
                        ->label('Coverage summary')
                        ->helperText('Short text describing the included time or coverage.')
                        ->placeholder('8 hours coverage')
                        ->maxLength(255),
                ])
                ->columns(3),

            Section::make('Deliverables')
                ->description('Add the main deliverables included in this package.')
                ->schema([
                    TagsInput::make('deliverables')
                        ->label('Deliverables')
                        ->helperText('Type one deliverable at a time and press enter after each one.')
                        ->placeholder('Add a deliverable and press enter')
                        ->reorderable(),
                ]),

            Section::make('Package Items')
                ->description('Add the individual package features or bullet points shown inside this package.')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->schema([
                            TextInput::make('label')
                                ->label('Item title')
                                ->helperText('The short title for this feature.')
                                ->placeholder('Teaser film')
                                ->required()
                                ->maxLength(255),

                            Textarea::make('description')
                                ->label('Item description')
                                ->helperText('Optional extra detail for this feature.')
                                ->placeholder('A short highlight film delivered soon after the wedding.')
                                ->rows(3),

                            TextInput::make('sort_order')
                                ->label('Display order')
                                ->helperText('Lower numbers appear first.')
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_highlighted')
                                ->label('Feature this item')
                                ->helperText('Turn this on to visually emphasize this item.')
                                ->default(false),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->columns(2),
                ]),

            Section::make('Visibility')
                ->description('Control whether this package is active and whether it receives extra emphasis.')
                ->schema([
                    Toggle::make('is_featured')
                        ->label('Feature this package')
                        ->helperText('Turn this on to highlight this package in featured areas.')
                        ->default(false),

                    Toggle::make('is_active')
                        ->label('Show this package on the website')
                        ->helperText('Turn this off to hide this package from the live site.')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->label('Display order')
                        ->helperText('Lower numbers appear first.')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),
        ]);
    }
}