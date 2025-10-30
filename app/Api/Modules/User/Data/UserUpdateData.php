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
class UserUpdateData extends Data implements DataSerializer
{
    use DataUtilsTrait;

    public function __construct(
        public string|Optional $name,
        public string|Optional $email,
        public string|Optional|null $picture,
        public string|Optional|null $birthdate,
        public string|Optional|null $gender,
        public string|Optional|null $phoneNumber,
        public string|Optional|null $phoneNumberCountryCode,
        public string|Optional|null $address,
        public string|Optional|null $addressNumber,
        public string|Optional|null $addressComplement,
        public string|Optional|null $addressNeighborhood,
        public string|Optional|null $addressCity,
        public string|Optional|null $addressState,
        public string|Optional|null $addressCountry,
        public string|Optional|null $addressPostalCode,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255'],
            'picture' => ['nullable', 'string', 'url', 'max:512'],
            'birthdate' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:16'],
            'phone_number' => ['nullable', 'string', 'max:32'],
            'phone_number_country_code' => ['nullable', 'string', 'max:8'],
            'address' => ['nullable', 'string', 'max:64'],
            'address_number' => ['nullable', 'string', 'max:16'],
            'address_complement' => ['nullable', 'string', 'max:16'],
            'address_neighborhood' => ['nullable', 'string', 'max:48'],
            'address_city' => ['nullable', 'string', 'max:48'],
            'address_state' => ['nullable', 'string', 'max:32'],
            'address_country' => ['nullable', 'string', 'max:32'],
            'address_postal_code' => ['nullable', 'string', 'max:32'],
        ];
    }
}

