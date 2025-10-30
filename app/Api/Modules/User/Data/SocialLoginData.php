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
class SocialLoginData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $provider, // facebook, google, apple, twitter
        public string $providerId, // ID do usuário no provider
        public string|Optional|null $email,
        public string|Optional $name,
        public string|Optional|null $picture,
        public string|Optional|null $accessToken, // Token do provider (para validação)
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'provider' => ['required', 'string', 'in:facebook,google,apple,twitter'],
            'provider_id' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'picture' => ['nullable', 'string', 'url', 'max:512'],
            'access_token' => ['nullable', 'string'],
        ];
    }
}

