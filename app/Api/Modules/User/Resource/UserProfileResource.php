<?php

namespace App\Api\Modules\User\Resource;

use App\Api\Support\Resources\BaseResource;
use Illuminate\Http\Request;

class UserProfileResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'picture' => $this->picture,
            'birthdate' => $this->birthdate?->format('Y-m-d'),
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'phone_number_country_code' => $this->phone_number_country_code,
            'phone_number_confirmed' => $this->phone_number_confirmed,
            'address' => $this->address,
            'address_number' => $this->address_number,
            'address_complement' => $this->address_complement,
            'address_neighborhood' => $this->address_neighborhood,
            'address_city' => $this->address_city,
            'address_state' => $this->address_state,
            'address_country' => $this->address_country,
            'address_postal_code' => $this->address_postal_code,
            'status' => $this->status,
            'register_complete' => $this->register_complete,
            'beta_tester' => $this->beta_tester,
            'is_pro_user' => $this->is_pro_user,
            'terms_and_conditions_accepted' => $this->terms_and_conditions_accepted,
            'privacy_accepted' => $this->privacy_accepted,
            'created_at' => $this->created_at?->toIso8601String(),
            'last_update_at' => $this->last_update_at?->toIso8601String(),
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'vet_id' => $this->vet_id,
            'ong_id' => $this->ong_id,
        ];
    }
}

