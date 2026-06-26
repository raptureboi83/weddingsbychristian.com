<?php

namespace App\Filament\Resources\PackageItems\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackageItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Item Details')
                    ->description('Add a single feature or bullet point that belongs to one package.')
                    ->schema([
                        Select::make('package_id')
                            ->label('Package')
                            ->helperText('Choose which package this item should appear in.')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('label')
                            ->label('Item title')
                            ->helperText('The short feature name shown in the package.')
                            ->placeholder('8 hours of coverage')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Item description')
                            ->helperText('Optional extra detail shown under the item title.')
                            ->placeholder('Coverage begins with getting ready and continues through the main reception events.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Toggle::make('is_highlighted')
                            ->label('Feature this item')
                            ->helperText('Turn this on to visually emphasize this item inside the package.')
                            ->default(false),

                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers appear first.')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}