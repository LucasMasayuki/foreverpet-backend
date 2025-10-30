<?php

namespace App\Api\Support\Services;

use App\Api\Support\Contracts\StorageServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Local Storage Service - Para desenvolvimento
 *
 * Usa o disco 'public' do Laravel
 */
class LocalStorageService implements StorageServiceInterface
{
    public function createSignedUploadUrl(
        string $path,
        string $filename,
        int $expirationMinutes = 60
    ): array {
        // Generate unique key
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueFilename = Str::uuid() . '.' . $extension;
        $key = trim($path, '/') . '/' . $uniqueFilename;

        // For local development, return a direct upload endpoint
        $uploadUrl = url('/api/v1/storage/upload');

        return [
            'url' => $uploadUrl,
            'key' => $key,
            'public_url' => Storage::url($key),
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'multipart/form-data',
            ],
            'fields' => [
                'key' => $key,
            ],
        ];
    }

    public function upload(
        string $path,
        string $filename,
        string $content,
        array $options = []
    ): string {
        // Generate unique key
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueFilename = Str::uuid() . '.' . $extension;
        $key = trim($path, '/') . '/' . $uniqueFilename;

        // Save to local storage
        Storage::disk('public')->put($key, $content);

        // Return public URL
        return Storage::url($key);
    }

    public function delete(string $key): bool
    {
        try {
            Storage::disk('public')->delete($key);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPublicUrl(string $key): string
    {
        return Storage::url($key);
    }

    public function exists(string $key): bool
    {
        return Storage::disk('public')->exists($key);
    }
}

