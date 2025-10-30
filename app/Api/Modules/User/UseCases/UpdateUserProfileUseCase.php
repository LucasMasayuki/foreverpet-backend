<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserUpdateData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserUpdateData $data): User
    {
        $user = Auth::guard('api')->user();

        // Update basic fields
        if (isset($data->name)) {
            $user->name = $data->name;
        }

        if (isset($data->email)) {
            $user->email = strtolower($data->email);
        }

        if (isset($data->picture)) {
            $user->picture = $data->picture;
        }

        if (isset($data->birthdate)) {
            $user->birthdate = $data->birthdate;
        }

        if (isset($data->gender)) {
            $user->gender = $data->gender;
        }

        // Phone number change resets confirmation
        if (isset($data->phoneNumber)) {
            if ($data->phoneNumber !== $user->phone_number ||
                ($data->phoneNumberCountryCode ?? '55') !== $user->phone_number_country_code) {
                $user->phone_number_confirmed = false;
            }
            $user->phone_number = $data->phoneNumber;
            $user->phone_number_country_code = $data->phoneNumberCountryCode ?? '55';
        }

        // Update address
        if (isset($data->address)) {
            $user->address = $data->address;
            $user->address_number = $data->addressNumber;
            $user->address_complement = $data->addressComplement;
            $user->address_neighborhood = $data->addressNeighborhood;
            $user->address_city = $data->addressCity;
            $user->address_state = $data->addressState;
            $user->address_country = $data->addressCountry;
            $user->address_postal_code = $data->addressPostalCode;

            // Mark registration as complete if address is filled
            $user->register_complete = true;

            // Update presumed address
            $user->presumed_address = $user->address;
            $user->presumed_address_number = $user->address_number;
            $user->presumed_address_complement = $user->address_complement;
            $user->presumed_address_neighborhood = $user->address_neighborhood;
            $user->presumed_address_city = $user->address_city;
            $user->presumed_address_state = $user->address_state;
            $user->presumed_address_country = $user->address_country;
            $user->presumed_address_postal_code = $user->address_postal_code;
            $user->presumed_address_filled_by_user = true;
        }

        $user->last_update_at = now();
        $user->save();

        return $user;
    }
}

