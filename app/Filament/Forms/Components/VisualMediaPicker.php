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
                $container = $component->getContainer();
                $fieldPath = $container ? $container->getStatePath() . '.' . $component->getName() : $fieldName;
                $state = $container ? $container->getRawState() : [];

                return [
                    'targetStatePath' => $fieldPath,
                    'existingPath' => data_get($state, $component->getName()),
                    'uploadDirectory' => $directory,
                    'imageOnly' => $imageOnly,
                    'acceptedFileTypes' => $acceptedFileTypes,
                ];
            })
            ->columnSpanFull();
    }
}
