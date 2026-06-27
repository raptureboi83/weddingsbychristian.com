<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChunkedMediaUploadController extends Controller
{
    public function chunk(Request $request)
    {
        try {
            $request->validate([
                'upload_id' => ['required', 'string', 'max:120'],
                'chunk_index' => ['required', 'integer', 'min:0'],
                'total_chunks' => ['required', 'integer', 'min:1'],
                'original_name' => ['required', 'string', 'max:255'],
                'chunk' => ['required', 'file'],
            ]);

            $uploadId = Str::of($request->string('upload_id'))->replaceMatches('/[^A-Za-z0-9\-_]/', '')->value();
            $chunkIndex = (int) $request->integer('chunk_index');
            $totalChunks = (int) $request->integer('total_chunks');
            $originalName = $request->string('original_name')->value();

            $tempDir = storage_path("app/chunked-media-uploads/{$uploadId}");

            if (! File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            File::put("{$tempDir}/meta.json", json_encode([
                'upload_id' => $uploadId,
                'original_name' => $originalName,
                'total_chunks' => $totalChunks,
                'updated_at' => now()->toISOString(),
            ], JSON_PRETTY_PRINT));

            $request->file('chunk')->move($tempDir, sprintf('%06d.part', $chunkIndex));

            return response()->json([
                'success' => true,
                'chunk_index' => $chunkIndex,
            ]);
        } catch (\Throwable $e) {
            Log::error('Media chunked upload chunk failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function finalize(Request $request)
    {
        try {
            $request->validate([
                'upload_id' => ['required', 'string', 'max:120'],
                'total_chunks' => ['required', 'integer', 'min:1'],
                'original_name' => ['required', 'string', 'max:255'],
                'directory' => ['required', 'string', 'max:255'],
            ]);

            $uploadId = Str::of($request->string('upload_id'))->replaceMatches('/[^A-Za-z0-9\-_]/', '')->value();
            $totalChunks = (int) $request->integer('total_chunks');
            $originalName = $request->string('original_name')->value();
            $directory = Str::of($request->string('directory'))->replaceMatches('/[^A-Za-z0-9\/\-_]/', '')->value();

            $tempDir = storage_path("app/chunked-media-uploads/{$uploadId}");

            if (! File::isDirectory($tempDir)) {
                Log::warning('Media chunked upload finalize: temp dir not found', ['upload_id' => $uploadId]);
                return response()->json([
                    'message' => 'Temporary upload folder not found.',
                ], 422);
            }

            for ($i = 0; $i < $totalChunks; $i++) {
                $partPath = "{$tempDir}/" . sprintf('%06d.part', $i);

                if (! File::exists($partPath)) {
                    Log::warning('Media chunked upload finalize: missing chunk', ['upload_id' => $uploadId, 'chunk' => $i]);
                    return response()->json([
                        'message' => "Missing chunk {$i}.",
                    ], 422);
                }
            }

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION) ?: 'bin');

            $finalRelativePath = $directory . '/' . now()->format('Y/m') . '/' . Str::uuid() . '.' . $extension;
            $finalAbsolutePath = storage_path('app/public/' . $finalRelativePath);
            $finalDirectory = dirname($finalAbsolutePath);

            if (! File::exists($finalDirectory)) {
                File::makeDirectory($finalDirectory, 0755, true);
            }

            $output = fopen($finalAbsolutePath, 'wb');

            if ($output === false) {
                Log::error('Media chunked upload finalize: could not create output file', ['path' => $finalAbsolutePath]);
                return response()->json([
                    'message' => 'Could not create output file.',
                ], 500);
            }

            try {
                for ($i = 0; $i < $totalChunks; $i++) {
                    $partPath = "{$tempDir}/" . sprintf('%06d.part', $i);
                    $input = fopen($partPath, 'rb');

                    if ($input === false) {
                        throw new \RuntimeException("Could not read chunk {$i}.");
                    }

                    while (! feof($input)) {
                        $buffer = fread($input, 1024 * 1024);
                        if ($buffer !== false) {
                            fwrite($output, $buffer);
                        }
                    }

                    fclose($input);
                }
            } finally {
                fclose($output);
            }

            File::deleteDirectory($tempDir);

            $mediaFile = MediaFile::createFromUpload(
                $finalRelativePath,
                $originalName,
            );

            return response()->json([
                'success' => true,
                'path' => $finalRelativePath,
                'url' => Storage::disk('public')->url($finalRelativePath),
                'media_file' => $mediaFile ? [
                    'path' => $mediaFile->file_path,
                    'name' => $mediaFile->name,
                    'url' => $mediaFile->url(),
                    'type' => $mediaFile->type,
                    'is_image' => $mediaFile->isImage(),
                    'is_video' => $mediaFile->isVideo(),
                    'size' => $mediaFile->humanSize(),
                ] : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Media chunked upload finalize failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 50);
        $page = (int) $request->integer('page', 1);
        $search = $request->input('search');

        $query = MediaFile::query()
            ->when($request->input('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('original_name', 'like', "%{$s}%");
            }))
            ->when($request->input('sort'), function ($q, $sort) use ($request) {
                $allowed = ['name', 'type', 'size', 'created_at'];
                $sort = in_array($sort, $allowed) ? $sort : 'created_at';
                $dir = $request->input('direction', 'desc') === 'asc' ? 'asc' : 'desc';
                $q->orderBy($sort, $dir);
            }, fn ($q) => $q->orderBy('created_at', 'desc'));

        $total = $query->count();
        $mediaFiles = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn (MediaFile $file) => [
                'id' => $file->id,
                'path' => $file->file_path,
                'name' => $file->name,
                'url' => $file->url(),
                'type' => $file->type,
                'is_image' => $file->isImage(),
                'is_video' => $file->isVideo(),
                'size' => $file->humanSize(),
            ]);

        $lastPage = (int) ceil($total / $perPage);

        return response()->json([
            'data' => $mediaFiles,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage,
            'next_page_url' => $page < $lastPage
                ? url()->current() . '?' . http_build_query(array_merge($request->except('page'), ['page' => $page + 1]))
                : null,
        ]);
    }
}
