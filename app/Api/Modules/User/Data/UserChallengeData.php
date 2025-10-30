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
class UserChallengeData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string|Optional|null $email,
        public string|Optional|null $phoneNumber,
        public string|Optional|null $phoneNumberCountryCode,
        public int|Optional $type, // 0=Register, 1=Login
        public string|Optional|null $appHash,
        public int|Optional|null $attempt,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'email' => ['required_without:phone_number', 'string', 'email'],
            'phone_number' => ['required_without:email', 'string', 'max:32'],
            'phone_number_country_code' => ['nullable', 'string', 'max:8'],
            'type' => ['required', 'integer', 'in:0,1'],
            'app_hash' => ['nullable', 'string'],
            'attempt' => ['nullable', 'integer'],
        ];
    }
}

