<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    protected $fillable = [
        'name',
        'original_name',
        'file_path',
        'type',
        'mime_type',
        'size',
        'disk',
        'alt_text',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function url(): ?string
    {
        $path = $this->file_path;

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['/'])) {
            return $path;
        }

        return Storage::disk($this->disk)->url($path);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'video/');
    }

    public function humanSize(): string
    {
        if (!$this->size) {
            return '—';
        }

        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

    public static function createFromUpload(string $path, ?string $name = null): self
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        $mimeType = $disk->exists($path) ? $disk->mimeType($path) : null;
        $size = $disk->exists($path) ? $disk->size($path) : null;

        $type = 'other';
        if ($mimeType) {
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            } elseif (str_starts_with($mimeType, 'application/pdf') || str_starts_with($mimeType, 'application/msword') || str_starts_with($mimeType, 'application/vnd.')) {
                $type = 'document';
            }
        }

        return static::create([
            'name' => $name ?: pathinfo($path, PATHINFO_FILENAME),
            'original_name' => basename($path),
            'file_path' => $path,
            'type' => $type,
            'mime_type' => $mimeType,
            'size' => $size,
            'disk' => 'public',
        ]);
    }
}
