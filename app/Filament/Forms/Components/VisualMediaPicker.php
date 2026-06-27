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
            ->viewData(function ($component) use ($directory, $acceptedFileTypes, $imageOnly, $fieldName) {
                $statePath = $component->getStatePath();

                $existing = null;
                $container = $component->getContainer();
                if ($container) {
                    try {
                        $existing = data_get($container->getRawState(), $component->getName());
                    } catch (\Throwable) {
                    }
                }

                return [
                    'targetStatePath' => $statePath,
                    'existingPath' => $existing,
                    'uploadDirectory' => $directory,
                    'imageOnly' => $imageOnly,
                    'acceptedFileTypes' => $acceptedFileTypes,
                ];
            })
            ->columnSpanFull();
    }
}
