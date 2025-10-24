<?php

namespace App\Api\Modules\User\Data;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class UserQueryData extends Data
{
    public function __construct(
        #[FromRouteParameter('userId')]
        public int $id,
    ) {}

    public static function rules(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('users', 'id')],
        ];
    }
}

