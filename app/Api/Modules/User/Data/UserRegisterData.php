<?php

namespace App\Api\Modules\User\Data;

use App\Api\Support\Contracts\DataSerializer;
use App\Api\Support\Traits\DataUtilsTrait;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
class UserRegisterData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $name,
        public string $email,
        public string|Optional|null $password,
        public string|Optional|null $picture,
        public string|Optional|null $facebookId,
        public string|Optional|null $googleId,
        public string|Optional|null $appleId,
        public string|Optional|null $twitterId,
        public string|Optional|null $phoneNumber,
        public string|Optional|null $phoneNumberCountryCode,
        public string|Optional|null $phoneNumberConfirmationCode,
        public string|Optional|null $phoneNumberChallenge,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'picture' => ['nullable', 'string', 'url', 'max:512'],
            'facebook_id' => ['nullable', 'string', 'max:128'],
            'google_id' => ['nullable', 'string', 'max:128'],
            'apple_id' => ['nullable', 'string', 'max:128'],
            'twitter_id' => ['nullable', 'string', 'max:128'],
            'phone_number' => ['nullable', 'string', 'max:32'],
            'phone_number_country_code' => ['nullable', 'string', 'max:8'],
            'phone_number_confirmation_code' => ['nullable', 'string', 'max:16'],
            'phone_number_challenge' => ['nullable', 'string'],
        ];
    }
}

