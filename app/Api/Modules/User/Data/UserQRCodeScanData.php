<?php

namespace App\Api\Modules\User\Data;

use App\Api\Support\Contracts\DataSerializer;
use App\Api\Support\Traits\DataUtilsTrait;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
class UserQRCodeScanData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $content,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }
}

