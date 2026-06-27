<?php

namespace App\Filament\Forms\Components;

use App\Models\MediaFile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class MediaPicker
{
    public static function make(
        string $fieldName,
        string $label = 'Media',
        string $directory = 'media',
        ?array $acceptedFileTypes = null,
        bool $imageOnly = false,
    ): Select {
        return Select::make($fieldName)
            ->label($label)
            ->helperText('Choose from existing media, or click "Create" to upload a new file.')
            ->searchable()
            ->allowHtml()
            ->options(
                fn () => static::buildOptions($imageOnly)
            )
            ->getOptionLabelUsing(fn (string $value): string => static::getOptionLabel($value))
            ->createOptionForm([
                ViewField::make('file_path')
                    ->label('Upload file')
                    ->view('filament.forms.components.chunked-media-upload')
                    ->viewData(function ($component) use ($directory) {
                        return [
                            'uploadDirectory' => $directory,
                            'livewireStatePath' => $component->getStatePath(),
                        ];
                    })
                    ->required(),

                TextInput::make('name')
                    ->label('Display name')
                    ->required()
                    ->maxLength(255),
            ])
            ->createOptionUsing(function (array $data) {
                $path = $data['file_path'] ?? null;

                if (!$path) {
                    return '';
                }

                $name = $data['name'] ?? pathinfo($path, PATHINFO_FILENAME);

                $mediaFile = MediaFile::createFromUpload($path, $name);

                return $mediaFile->file_path;
            });
    }

    protected static function getOptionLabel(string $filePath): string
    {
        $file = MediaFile::where('file_path', $filePath)->first();

        if (!$file) {
            return $filePath;
        }

        return static::optionHtml($file);
    }

    protected static function buildOptions(bool $imageOnly): array
    {
        $query = MediaFile::query()
            ->orderBy('created_at', 'desc');

        if ($imageOnly) {
            $query->where('type', 'image');
        }

        return $query->get()->mapWithKeys(fn (MediaFile $file) => [
            $file->file_path => static::optionHtml($file),
        ])->toArray();
    }

    protected static function optionHtml(MediaFile $file): string
    {
        if ($file->isImage()) {
            $preview = '<img src="' . $file->url() . '" class="w-10 h-10 object-cover rounded shrink-0" />';
        } elseif ($file->isVideo()) {
            $preview = '<span class="w-10 h-10 shrink-0 flex items-center justify-center bg-gray-100 rounded text-gray-500 text-xs font-medium">VID</span>';
        } else {
            $preview = '<span class="w-10 h-10 shrink-0 flex items-center justify-center bg-gray-100 rounded text-gray-500 text-xs font-medium">FILE</span>';
        }

        $name = e($file->name);
        $path = e($file->file_path);

        return <<<HTML
<div class="flex items-center gap-3 py-1">
    {$preview}
    <div class="flex flex-col min-w-0">
        <span class="text-sm font-medium truncate">{$name}</span>
        <span class="text-xs text-gray-400 truncate">{$path}</span>
    </div>
</div>
HTML;
    }
}
