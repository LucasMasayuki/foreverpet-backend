<?php

namespace App\Api\Modules\User\Controllers;

use App\Api\Http\Controllers\ApiBaseController;
use App\Api\Modules\User\Data\CreateSignedUploadUrlData;
use App\Api\Modules\User\Data\UserDeviceData;
use App\Api\Modules\User\Data\UserQRCodeScanData;
use App\Api\Modules\User\Data\UserUpdateData;
use App\Api\Modules\User\Resource\UserBasicResource;
use App\Api\Modules\User\Resource\UserProfileResource;
use App\Api\Modules\User\UseCases\CreateSignedUploadUrlUseCase;
use App\Api\Modules\User\UseCases\GetUserProfileUseCase;
use App\Api\Modules\User\UseCases\ScanQRCodeUseCase;
use App\Api\Modules\User\UseCases\UpdateUserDeviceUseCase;
use App\Api\Modules\User\UseCases\UpdateUserProfileUseCase;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserProfileController extends ApiBaseController
{
    /**
     * GET /rest/Users - Get authenticated user profile
     */
    public function show(GetUserProfileUseCase $useCase): UserProfileResource
    {
        $user = $useCase->execute();

        return new UserProfileResource($user);
    }

    /**
     * GET /rest/Users/{id} - Get basic user info by ID
     */
    public function getBasicInfo(string $id): UserBasicResource
    {
        $user = User::findOrFail($id);

        return new UserBasicResource($user);
    }

    /**
     * POST /rest/Users - Update user profile
     */
    public function update(UserUpdateData $data, UpdateUserProfileUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([]);
    }

    /**
     * POST /rest/Users/QRCode/Scan - Scan QR Code
     */
    public function scanQrCode(UserQRCodeScanData $data, ScanQRCodeUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([]);
    }

    /**
     * POST /rest/Users/Devices - Update device info
     */
    public function updateDevice(UserDeviceData $data, UpdateUserDeviceUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([]);
    }

    /**
     * POST /rest/Users/CreateSignedUploadUrl - Create signed upload URL
     */
    public function createSignedUploadUrl(
        CreateSignedUploadUrlData $data,
        CreateSignedUploadUrlUseCase $useCase
    ): JsonResponse {
        $result = $useCase->execute($data);

        return response()->json($result);
    }
}

