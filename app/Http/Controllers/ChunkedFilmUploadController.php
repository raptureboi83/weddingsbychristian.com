<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChunkedFilmUploadController extends Controller
{
    public function chunk(Request $request)
    {
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

        $tempDir = storage_path("app/chunked-film-uploads/{$uploadId}");

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
    }

    public function finalize(Request $request)
    {
        $request->validate([
            'upload_id' => ['required', 'string', 'max:120'],
            'total_chunks' => ['required', 'integer', 'min:1'],
            'original_name' => ['required', 'string', 'max:255'],
        ]);

        $uploadId = Str::of($request->string('upload_id'))->replaceMatches('/[^A-Za-z0-9\-_]/', '')->value();
        $totalChunks = (int) $request->integer('total_chunks');
        $originalName = $request->string('original_name')->value();

        $tempDir = storage_path("app/chunked-film-uploads/{$uploadId}");

        if (! File::isDirectory($tempDir)) {
            return response()->json([
                'message' => 'Temporary upload folder not found.',
            ], 422);
        }

        for ($i = 0; $i < $totalChunks; $i++) {
            $partPath = "{$tempDir}/" . sprintf('%06d.part', $i);

            if (! File::exists($partPath)) {
                return response()->json([
                    'message' => "Missing chunk {$i}.",
                ], 422);
            }
        }

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION) ?: 'mp4');
        $safeExtension = in_array($extension, ['mp4', 'mov', 'webm'], true) ? $extension : 'mp4';

        $finalRelativePath = 'films/videos/' . now()->format('Y/m') . '/' . Str::uuid() . '.' . $safeExtension;
        $finalAbsolutePath = storage_path('app/public/' . $finalRelativePath);
        $finalDirectory = dirname($finalAbsolutePath);

        if (! File::exists($finalDirectory)) {
            File::makeDirectory($finalDirectory, 0755, true);
        }

        $output = fopen($finalAbsolutePath, 'wb');

        if ($output === false) {
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

        return response()->json([
            'success' => true,
            'path' => $finalRelativePath,
            'url' => Storage::disk('public')->url($finalRelativePath),
        ]);
    }
}