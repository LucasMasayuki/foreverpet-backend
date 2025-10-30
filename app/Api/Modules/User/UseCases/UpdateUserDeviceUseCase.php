<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserDeviceData;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserDeviceHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UpdateUserDeviceUseCase
{
    public function execute(UserDeviceData $data): void
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        // Find or create device
        $device = UserDevice::where('user_id', $user->id)
            ->where('device_uuid', $data->deviceUuid)
            ->first();

        if ($device) {
            $this->updateDevice($device, $data);
        } else {
            $device = $this->createDevice($user, $data);
        }

        // Unregister other devices with same Firebase token
        if (!empty($data->firebaseToken)) {
            $this->unregisterDuplicateDevices($user->id, $device->id, $data->firebaseToken);
        }

        // Update or create device history
        $this->updateDeviceHistory($device, $data);
    }

    private function updateDevice(UserDevice $device, UserDeviceData $data): void
    {
        $device->logged_in = $data->loggedIn ?? $device->logged_in;
        $device->firebase_token = $data->firebaseToken ?? $device->firebase_token;
        $device->language = $data->language ?? $device->language;
        $device->platform = $data->platform ?? $device->platform ?? 'unknown';
        $device->last_update_at = now();
        $device->advertisement_id = $data->advertisementId ?? $device->advertisement_id;
        $device->advertisement_tracking_limited = $data->advertisementTrackingLimited || $device->advertisement_tracking_limited;

        if (abs($data->latitude ?? 0) > 0.01 || abs($data->longitude ?? 0) > 0.01) {
            $device->latitude = $data->latitude;
            $device->longitude = $data->longitude;
        }

        if (!empty($data->firebaseToken)) {
            $device->unregistered = false;
            $device->unregistered_at = null;
        }

        if ($data->appVersion) {
            $device->app_version = $data->appVersion;
        }

        $device->save();
    }

    private function createDevice(User $user, UserDeviceData $data): UserDevice
    {
        $device = new UserDevice();
        $device->id = Str::uuid()->toString();
        $device->user_id = $user->id;
        $device->device_uuid = $data->deviceUuid;
        $device->firebase_token = $data->firebaseToken;
        $device->logged_in = $data->loggedIn ?? true;
        $device->language = $data->language ?? 'en';
        $device->latitude = $data->latitude;
        $device->longitude = $data->longitude;
        $device->platform = $data->platform ?? 'unknown';
        $device->advertisement_id = $data->advertisementId;
        $device->advertisement_tracking_limited = $data->advertisementTrackingLimited ?? false;
        $device->app_version = $data->appVersion;
        $device->created_at = now();
        $device->save();

        return $device;
    }

    private function unregisterDuplicateDevices(string $userId, string $currentDeviceId, string $firebaseToken): void
    {
        UserDevice::where('user_id', $userId)
            ->where('id', '!=', $currentDeviceId)
            ->where('firebase_token', $firebaseToken)
            ->where('unregistered', false)
            ->update([
                'unregistered' => true,
                'unregistered_at' => now(),
            ]);
    }

    private function updateDeviceHistory(UserDevice $device, UserDeviceData $data): void
    {
        $lastHistory = UserDeviceHistory::where('user_device_id', $device->id)
            ->orderBy('last_update_at', 'desc')
            ->first();

        $shouldCreateNew = $lastHistory === null ||
            ($lastHistory->app_version && $lastHistory->app_version !== $data->appVersion) ||
            $this->isOutsideBoundingBox($lastHistory, $data);

        if ($shouldCreateNew) {
            $this->createDeviceHistory($device, $data);
        } else {
            $this->updateExistingHistory($lastHistory, $data);
        }
    }

    private function isOutsideBoundingBox(?UserDeviceHistory $history, UserDeviceData $data): bool
    {
        if (!$history || abs($data->latitude ?? 0) <= 0.01 || abs($data->longitude ?? 0) <= 0.01) {
            return false;
        }

        // 50km bounding box (simplified)
        $latDiff = 0.45; // ~50km
        $lonDiff = 0.45;

        return $history->latitude < ($data->latitude - $latDiff) ||
               $history->latitude > ($data->latitude + $latDiff) ||
               $history->longitude < ($data->longitude - $lonDiff) ||
               $history->longitude > ($data->longitude + $lonDiff);
    }

    private function createDeviceHistory(UserDevice $device, UserDeviceData $data): void
    {
        $history = new UserDeviceHistory();
        $history->id = Str::uuid()->toString();
        $history->user_device_id = $device->id;
        $history->latitude = $data->latitude;
        $history->longitude = $data->longitude;
        $history->app_version = $data->appVersion;
        $history->update_count = 0;
        $history->created_at = now();
        $history->last_update_at = now();
        $history->save();
    }

    private function updateExistingHistory(UserDeviceHistory $history, UserDeviceData $data): void
    {
        $history->last_update_at = now();

        if (abs($data->latitude ?? 0) > 0.01 || abs($data->longitude ?? 0) > 0.01) {
            $history->latitude = $data->latitude;
            $history->longitude = $data->longitude;
        }

        if ($history->update_count < PHP_INT_MAX) {
            $history->update_count++;
        }

        if ($data->appVersion) {
            $history->app_version = $data->appVersion;
        }

        $history->save();
    }
}

