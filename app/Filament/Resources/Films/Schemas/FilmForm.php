<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Film Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        DatePicker::make('wedding_date'),

                        TextInput::make('location')
                            ->maxLength(255),

                        TextInput::make('venue')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('video_path')
                            ->label('Video File')
                            ->disk('public')
                            ->directory('films/videos')
                            ->required()
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/webm'])
                            ->columnSpanFull(),

                        FileUpload::make('thumbnail_path')
                            ->label('Thumbnail Image')
                            ->disk('public')
                            ->directory('films/thumbnails')
                            ->image()
                            ->required(),

                        TextInput::make('thumbnail_alt')
                            ->label('Thumbnail Alt Text')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Visibility')
                    ->schema([
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false),
                    ])
                    ->columns(3),
            ]);
    }
}