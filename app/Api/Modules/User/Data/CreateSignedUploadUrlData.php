<?php

namespace App\Api\Modules\User\Data;

use App\Api\Support\Contracts\DataSerializer;
use App\Api\Support\Traits\DataUtilsTrait;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
class CreateSignedUploadUrlData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string $extension,      // Extensão do arquivo (jpg, png, pdf, etc)
        public string $remoteDir,      // Diretório remoto (users, pets, documents, etc)
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'extension' => ['required', 'string', 'in:jpg,jpeg,png,gif,webp,pdf,mp4,mov'],
            'remote_dir' => ['required', 'string', 'in:users,pets,documents,exams,prescriptions'],
        ];
    }
}

