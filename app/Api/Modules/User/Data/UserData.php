<?php

namespace App\Api\Modules\User\Data;

use App\Api\Support\Contracts\DataSerializer;
use App\Api\Support\Traits\DataUtilsTrait;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
class UserData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        #[FromRouteParameter('userId')]
        public int|Optional $id,
        public string $name,
        public string $email,
        public string|Optional|null $password,
    ) {
        // Validações customizadas se necessário
    }

    public static function rules(ValidationContext $context): array
    {
        $userId = $context->payload['id'] ?? null;

        return [
            'id' => ['integer', Rule::exists('users', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}

