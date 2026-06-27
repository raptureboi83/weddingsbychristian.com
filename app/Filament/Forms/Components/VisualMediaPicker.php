<?php

namespace App\Filament\Forms\Components;

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
                $initialValue = null;
                try {
                    $initialValue = $component->getState();
                } catch (\Throwable) {
                }

                return [
                    'targetStatePath' => $component->getStatePath(),
                    'initialValue' => $initialValue,
                    'uploadDirectory' => $directory,
                    'imageOnly' => $imageOnly,
                    'acceptedFileTypes' => $acceptedFileTypes,
                ];
            })
            ->columnSpanFull();
    }
}
