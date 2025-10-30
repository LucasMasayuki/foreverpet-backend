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
class UserPhoneNumberData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $phoneNumber,
        public string|Optional|null $phoneNumberCountryCode,
        public string|Optional|null $phoneNumberConfirmationCode,
        public string|Optional|null $challenge,
        public string|Optional|null $email,
        public string|Optional|null $appHash,
        public int|Optional|null $attempt,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'phone_number' => ['required', 'string', 'max:32'],
            'phone_number_country_code' => ['nullable', 'string', 'max:8'],
            'phone_number_confirmation_code' => ['nullable', 'string', 'max:16'],
            'challenge' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'app_hash' => ['nullable', 'string'],
            'attempt' => ['nullable', 'integer'],
        ];
    }
}

