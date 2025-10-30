<?php

namespace App\Api\Modules\User\Data;

use App\Api\Support\Contracts\DataSerializer;
use App\Api\Support\Traits\DataUtilsTrait;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
class UserDeviceData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $deviceUuid,
        public string|Optional|null $firebaseToken,
        public bool|Optional|null $loggedIn,
        public string|Optional|null $language,
        public string|Optional|null $platform,
        public float|Optional|null $latitude,
        public float|Optional|null $longitude,
        public string|Optional|null $advertisementId,
        public bool|Optional $advertisementTrackingLimited,
        public int|Optional|null $appVersion,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'device_uuid' => ['required', 'string', 'max:255'],
            'firebase_token' => ['nullable', 'string'],
            'logged_in' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:10'],
            'platform' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'advertisement_id' => ['nullable', 'string'],
            'advertisement_tracking_limited' => ['nullable', 'boolean'],
            'app_version' => ['nullable', 'integer'],
        ];
    }
}

