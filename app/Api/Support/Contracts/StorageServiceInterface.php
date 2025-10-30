<?php

namespace App\Api\Support\Contracts;

interface StorageServiceInterface
{
    /**
     * Generate a signed URL for direct upload to storage
     *
     * @param string $path Path in storage bucket
     * @param string $filename Original filename
     * @param int $expirationMinutes URL expiration time in minutes
     * @return array ['url' => signed_url, 'key' => storage_key, 'fields' => form_fields]
     */
    public function createSignedUploadUrl(
        string $path,
        string $filename,
        int $expirationMinutes = 60
    ): array;

    /**
     * Upload file directly to storage
     *
     * @param string $path Path in storage bucket
     * @param string $filename Original filename
     * @param string $content File content
     * @param array $options Additional options (visibility, metadata, etc)
     * @return string Public URL of uploaded file
     */
    public function upload(
        string $path,
        string $filename,
        string $content,
        array $options = []
    ): string;

    /**
     * Delete file from storage
     *
     * @param string $key Storage key/path
     * @return bool Success status
     */
    public function delete(string $key): bool;

    /**
     * Get public URL for a file
     *
     * @param string $key Storage key/path
     * @return string Public URL
     */
    public function getPublicUrl(string $key): string;

    /**
     * Check if file exists
     *
     * @param string $key Storage key/path
     * @return bool
     */
    public function exists(string $key): bool;
}

