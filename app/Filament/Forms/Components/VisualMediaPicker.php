<?php

namespace App\Filament\Forms\Components;

use App\Models\MediaFile;
use Filament\Forms\Components\ViewField;

class VisualMediaPicker
{
    public static function make(
        string $fieldName,
        string $label = 'Media',
        string $directory = 'media',
        ?array $acceptedFileTypes = null,
        bool $imageOnly = false,
    ): ViewField {
        return ViewField::make($fieldName)
            ->label($label)
            ->view('filament.forms.components.visual-media-picker')
            ->viewData(function ($component) use ($directory, $acceptedFileTypes, $imageOnly) {
                $fieldPath = $component->getStatePath();

                $mediaFiles = MediaFile::query()
                    ->when($imageOnly, fn ($q) => $q->where('type', 'image'))
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get()
                    ->map(fn (MediaFile $file) => [
                        'path' => $file->file_path,
                        'name' => $file->name,
                        'url' => $file->url(),
                        'type' => $file->type,
                        'is_image' => $file->isImage(),
                        'is_video' => $file->isVideo(),
                        'size' => $file->humanSize(),
                    ]);

                return [
                    'mediaFiles' => $mediaFiles,
                    'uploadDirectory' => $directory,
                    'livewireStatePath' => $fieldPath,
                    'acceptedFileTypes' => $acceptedFileTypes,
                    'imageOnly' => $imageOnly,
                    'uploadChunkUrl' => route('filament.admin.media.upload.chunk'),
                    'finalizeUrl' => route('filament.admin.media.upload.finalize'),
                ];
            })
            ->columnSpanFull();
    }
}
