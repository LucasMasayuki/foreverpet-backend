<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\CreateSignedUploadUrlData;
use App\Api\Support\Contracts\StorageServiceInterface;
use Illuminate\Support\Facades\Auth;

class CreateSignedUploadUrlUseCase
{
    public function __construct(
        private StorageServiceInterface $storageService
    ) {}

    public function execute(CreateSignedUploadUrlData $data): array
    {
        $user = Auth::guard('api')->user();

        // Generate path based on remote directory and user ID
        $path = $this->generatePath($data->remoteDir, $user->id);

        // Generate a temporary filename
        $filename = 'upload_' . time() . '.' . $data->extension;

        // Create signed URL (valid for 60 minutes)
        $signedData = $this->storageService->createSignedUploadUrl(
            $path,
            $filename,
            60
        );

        return [
            'upload_url' => $signedData['url'],
            'method' => $signedData['method'],
            'headers' => $signedData['headers'],
            'fields' => $signedData['fields'] ?? [],
            'key' => $signedData['key'],
            'public_url' => $signedData['public_url'],
            'expires_in' => 3600, // 60 minutes in seconds
        ];
    }

    private function generatePath(string $remoteDir, string $userId): string
    {
        $year = date('Y');
        $month = date('m');

        return match($remoteDir) {
            'users' => "uploads/users/{$userId}/profile",
            'pets' => "uploads/users/{$userId}/pets/{$year}/{$month}",
            'documents' => "uploads/users/{$userId}/documents/{$year}/{$month}",
            'exams' => "uploads/users/{$userId}/exams/{$year}/{$month}",
            'prescriptions' => "uploads/users/{$userId}/prescriptions/{$year}/{$month}",
            default => "uploads/users/{$userId}/misc/{$year}/{$month}",
        };
    }
}

