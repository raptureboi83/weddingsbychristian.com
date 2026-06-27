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
                $statePath = $component->getStatePath();
                $fieldName = $component->getName();
                $initialValue = null;
                $container = $component->getContainer();
                if ($container) {
                    try {
                        $raw = $container->getRawState();
                        $initialValue = data_get($raw, $statePath) ?? data_get($raw, $fieldName);
                    } catch (\Throwable) {
                    }
                }

                return [
                    'targetStatePath' => $statePath,
                    'initialValue' => $initialValue,
                    'uploadDirectory' => $directory,
                    'imageOnly' => $imageOnly,
                    'acceptedFileTypes' => $acceptedFileTypes,
                ];
            })
            ->columnSpanFull();
    }
}
