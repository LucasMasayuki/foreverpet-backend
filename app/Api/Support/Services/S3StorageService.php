<?php

namespace App\Api\Support\Services;

use App\Api\Support\Contracts\StorageServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3StorageService implements StorageServiceInterface
{
    private string $disk;
    private string $bucket;
    private bool $enabled;

    public function __construct()
    {
        $this->disk = config('filesystems.cloud', 's3');
        $this->bucket = config('filesystems.disks.s3.bucket', '');
        $this->enabled = config('services.s3.enabled', false);
    }

    public function createSignedUploadUrl(
        string $path,
        string $filename,
        int $expirationMinutes = 60
    ): array {
        if (!$this->enabled) {
            Log::warning('S3 not enabled, returning mock URL');
            return $this->mockSignedUrl($path, $filename);
        }

        // Generate unique key
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueFilename = Str::uuid() . '.' . $extension;
        $key = trim($path, '/') . '/' . $uniqueFilename;

        try {
            // Generate pre-signed POST data for direct upload
            $s3Client = Storage::disk($this->disk)->getClient();

            $postObject = $s3Client->createPresignedRequest(
                $s3Client->getCommand('PutObject', [
                    'Bucket' => $this->bucket,
                    'Key' => $key,
                    'ContentType' => $this->getMimeType($extension),
                    'ACL' => 'public-read',
                ]),
                '+' . $expirationMinutes . ' minutes'
            );

            $signedUrl = (string) $postObject->getUri();

            Log::info('S3 signed URL created', [
                'key' => $key,
                'expires_in' => $expirationMinutes . ' minutes',
            ]);

            return [
                'url' => $signedUrl,
                'key' => $key,
                'public_url' => $this->getPublicUrl($key),
                'method' => 'PUT',
                'headers' => [
                    'Content-Type' => $this->getMimeType($extension),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to create S3 signed URL', [
                'error' => $e->getMessage(),
                'path' => $path,
                'filename' => $filename,
            ]);
            throw $e;
        }
    }

    public function upload(
        string $path,
        string $filename,
        string $content,
        array $options = []
    ): string {
        if (!$this->enabled) {
            Log::warning('S3 not enabled, skipping upload');
            return 'https://via.placeholder.com/400x400?text=S3+Disabled';
        }

        // Generate unique key
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueFilename = Str::uuid() . '.' . $extension;
        $key = trim($path, '/') . '/' . $uniqueFilename;

        try {
            $options = array_merge([
                'visibility' => 'public',
                'ContentType' => $this->getMimeType($extension),
            ], $options);

            Storage::disk($this->disk)->put($key, $content, $options);

            $publicUrl = $this->getPublicUrl($key);

            Log::info('File uploaded to S3', [
                'key' => $key,
                'size' => strlen($content),
            ]);

            return $publicUrl;
        } catch (\Exception $e) {
            Log::error('Failed to upload to S3', [
                'error' => $e->getMessage(),
                'key' => $key,
            ]);
            throw $e;
        }
    }

    public function delete(string $key): bool
    {
        if (!$this->enabled) {
            Log::warning('S3 not enabled, skipping delete');
            return true;
        }

        try {
            Storage::disk($this->disk)->delete($key);

            Log::info('File deleted from S3', ['key' => $key]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete from S3', [
                'error' => $e->getMessage(),
                'key' => $key,
            ]);
            return false;
        }
    }

    public function getPublicUrl(string $key): string
    {
        if (!$this->enabled) {
            return 'https://via.placeholder.com/400x400?text=' . urlencode($key);
        }

        try {
            return Storage::disk($this->disk)->url($key);
        } catch (\Exception $e) {
            Log::error('Failed to get S3 public URL', [
                'error' => $e->getMessage(),
                'key' => $key,
            ]);
            return '';
        }
    }

    public function exists(string $key): bool
    {
        if (!$this->enabled) {
            return false;
        }

        try {
            return Storage::disk($this->disk)->exists($key);
        } catch (\Exception $e) {
            Log::error('Failed to check S3 file existence', [
                'error' => $e->getMessage(),
                'key' => $key,
            ]);
            return false;
        }
    }

    private function getMimeType(string $extension): string
    {
        return match(strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            default => 'application/octet-stream',
        };
    }

    private function mockSignedUrl(string $path, string $filename): array
    {
        $key = trim($path, '/') . '/' . Str::uuid() . '_' . $filename;

        return [
            'url' => 'https://mock-s3-upload-url.local/' . $key,
            'key' => $key,
            'public_url' => 'https://via.placeholder.com/400x400?text=Mock+Upload',
            'method' => 'PUT',
            'headers' => [
                'Content-Type' => $this->getMimeType(pathinfo($filename, PATHINFO_EXTENSION)),
            ],
        ];
    }
}

