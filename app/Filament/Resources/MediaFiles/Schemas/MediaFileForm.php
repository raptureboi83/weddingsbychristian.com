<?php

namespace App\Filament\Resources\MediaFiles\Schemas;

use App\Filament\Resources\MediaFiles\Pages\CreateMediaFile;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaFileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'xl' => 2])
            ->components([
                Section::make('File')
                    ->description('Upload a file or edit its details.')
                    ->columnSpan(fn ($livewire) => $livewire instanceof CreateMediaFile ? 1 : 2)
                    ->schema([
                        ViewField::make('file_path')
                            ->label('File')
                            ->helperText('Upload an image, video, or other file.')
                            ->view('filament.forms.components.chunked-media-upload')
                            ->viewData(function ($component) {
                                return [
                                    'uploadDirectory' => 'media',
                                    'livewireStatePath' => $component->getStatePath(),
                                ];
                            })
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Display name')
                            ->helperText('A friendly name shown in the media list.')
                            ->placeholder('Our wedding highlight reel')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('alt_text')
                            ->label('Alt text')
                            ->helperText('Descriptive text for accessibility and SEO.')
                            ->placeholder('A couple walking on the beach at sunset')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'xl' => 2]),

                Section::make('Bulk Upload')
                    ->description('Drag and drop many files to upload them in one pass. Uploaded files are created in the media library automatically.')
                    ->visible(fn ($livewire) => $livewire instanceof CreateMediaFile)
                    ->columnSpan(['xl' => 1])
                    ->schema([
                        ViewField::make('bulk_upload')
                            ->dehydrated(false)
                            ->view('filament.forms.components.chunked-media-bulk-upload')
                            ->viewData([
                                'uploadDirectory' => 'media',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
