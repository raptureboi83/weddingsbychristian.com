<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'xl' => 2,
            ])
            ->components([
                Section::make('Film Details')
                    ->description('Add the main information for a wedding film shown on your Films or Portfolio page.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Film title')
                            ->helperText('The main title visitors will see for this film.')
                            ->placeholder('Diana & Cory')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL name')
                            ->helperText('Used in the page link. This is usually filled automatically from the title.')
                            ->placeholder('diana-cory')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        DatePicker::make('wedding_date')
                            ->label('Wedding date')
                            ->helperText('Optional. Shows when the wedding took place.'),

                        TextInput::make('location')
                            ->label('Location')
                            ->helperText('City, state, or general location for the wedding.')
                            ->placeholder('Charleston, SC')
                            ->maxLength(255),

                        TextInput::make('venue')
                            ->label('Venue')
                            ->helperText('The venue name, if you want it shown with the film.')
                            ->placeholder('Lowndes Grove')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional text that describes the couple, the day, or the film.')
                            ->placeholder('A romantic coastal wedding film with heartfelt vows and elegant details.')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Media')
                    ->description('Upload the film file and the thumbnail image used to represent it on the site.')
                    ->schema([
                        Hidden::make('video_path')
                            ->required()
                            ->dehydrated(true),

                        Hidden::make('thumbnail_path')
                            ->dehydrated(true),

                        ViewField::make('video_path_ui')
                            ->label('Film video')
                            ->helperText('Upload a new video or choose one from the media library.')
                            ->view('filament.forms.components.media-picker-with-library')
                            ->viewData(function ($component) {
                                $state = $component->getContainer()->getRawState();
                                return [
                                    'targetStatePath' => $component->getContainer()->getStatePath() . '.video_path',
                                    'existingPath' => data_get($state, 'video_path'),
                                    'uploadDirectory' => 'media',
                                    'imageOnly' => false,
                                ];
                            })
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        ViewField::make('thumbnail_path_ui')
                            ->label('Thumbnail image')
                            ->helperText('Upload a new thumbnail or choose one from the media library.')
                            ->view('filament.forms.components.media-picker-with-library')
                            ->viewData(function ($component) {
                                $state = $component->getContainer()->getRawState();
                                return [
                                    'targetStatePath' => $component->getContainer()->getStatePath() . '.thumbnail_path',
                                    'existingPath' => data_get($state, 'thumbnail_path'),
                                    'uploadDirectory' => 'films/thumbnails',
                                    'imageOnly' => true,
                                ];
                            })
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        TextInput::make('thumbnail_alt')
                            ->label('Thumbnail alt text')
                            ->helperText('A short description of the thumbnail image for accessibility.')
                            ->placeholder('Bride and groom embracing outdoors at sunset')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Visibility')
                    ->description('Control where this film appears and whether it is visible on the live site.')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers appear first.')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label('Show this film on the website')
                            ->helperText('Turn this off to hide the film from the live site.')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Feature this film')
                            ->helperText('Turn this on to give this film extra emphasis in featured areas.')
                            ->default(false),
                    ])
                    ->columns(1)
                        ->columnSpanFull(),

            ]);
    }
}