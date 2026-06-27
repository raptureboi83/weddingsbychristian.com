<?php

namespace App\Filament\Resources\MediaFiles\Pages;

use App\Filament\Resources\MediaFiles\MediaFileResource;
use App\Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMediaFile extends CreateRecord
{
    protected static string $resource = MediaFileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['file_path'])) {
            $path = $data['file_path'];

            $data['original_name'] = basename($path);

            if (!$data['name'] ?? null) {
                $data['name'] = pathinfo($path, PATHINFO_FILENAME);
            }

            $disk = Storage::disk('public');
            if ($disk->exists($path)) {
                $data['mime_type'] = $disk->mimeType($path);
                $data['size'] = $disk->size($path);

                $mime = $data['mime_type'] ?? '';
                if (str_starts_with($mime, 'image/')) {
                    $data['type'] = 'image';
                } elseif (str_starts_with($mime, 'video/')) {
                    $data['type'] = 'video';
                } elseif (str_starts_with($mime, 'application/pdf') || str_starts_with($mime, 'application/msword') || str_starts_with($mime, 'application/vnd.')) {
                    $data['type'] = 'document';
                } else {
                    $data['type'] = 'other';
                }
            }
        }

        return $data;
    }
}
