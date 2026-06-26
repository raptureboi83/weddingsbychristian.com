<?php

namespace App\Filament\Resources\VendorCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VendorCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->description('Create or edit a vendor category, such as Photographers, Venues, or DJs.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category name')
                            ->helperText('The category title visitors will see.')
                            ->placeholder('Photographers')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL name')
                            ->helperText('Used in the page link. This is usually filled automatically from the category name.')
                            ->placeholder('photographers')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional text describing this category.')
                            ->placeholder('Trusted photographers I love working with.')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers appear first.')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label('Show this category on the website')
                            ->helperText('Turn this off to hide this category from the live site.')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}