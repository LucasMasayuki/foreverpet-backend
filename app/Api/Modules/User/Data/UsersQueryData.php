<?php

namespace App\Api\Modules\User\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UsersQueryData extends Data
{
    public function __construct(
        public string|Optional|null $search,
        public int|Optional $page = 1,
        public int|Optional $perPage = 15,
        public string|Optional $sortBy = 'id',
        public string|Optional $sortDirection = 'asc',
    ) {}

    public static function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:100'],
            'sort_by' => ['string', 'in:id,name,email,created_at'],
            'sort_direction' => ['string', 'in:asc,desc'],
        ];
    }
}

