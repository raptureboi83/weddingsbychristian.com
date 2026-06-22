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
                    ->schema([
                        Select::make('package_id')
                            ->label('Package')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('label')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),

                        Toggle::make('is_highlighted')
                            ->label('Highlighted item')
                            ->default(false),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}