<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserRegisterData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterUserUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserRegisterData $data): array
    {
        // Check if user already exists (social login)
        $existing = $this->repository->findBySocialOrEmail($data);

        if ($existing) {
            // Update social IDs if needed
            if ($this->shouldUpdateSocialLogin($existing, $data)) {
                $this->updateSocialLogin($existing, $data);
            }

            return ['is_new' => false, 'user' => $existing];
        }

        // Check if email already exists (non-social)
        if (!$this->isSocialLogin($data) && $this->repository->emailExists($data->email)) {
            throw new ValidationException('O E-mail informado já está sendo utilizado em outro cadastro.');
        }

        // Create new user
        $user = $this->createUser($data);

        // Send verification email
        // TODO: Implement SendVerificationEmailJob

        return ['is_new' => true, 'user' => $user];
    }

    private function isSocialLogin(UserRegisterData $data): bool
    {
        return !empty($data->facebookId) ||
               !empty($data->googleId) ||
               !empty($data->appleId) ||
               !empty($data->twitterId);
    }

    private function shouldUpdateSocialLogin(User $user, UserRegisterData $data): bool
    {
        return ($data->facebookId && $user->facebook_id !== $data->facebookId) ||
               ($data->googleId && $user->google_id !== $data->googleId) ||
               ($data->appleId && $user->apple_id !== $data->appleId) ||
               ($data->twitterId && $user->twitter_id !== $data->twitterId) ||
               ($data->picture && $user->picture !== $data->picture);
    }

    private function updateSocialLogin(User $user, UserRegisterData $data): void
    {
        $user->facebook_id = $data->facebookId ?? $user->facebook_id;
        $user->google_id = $data->googleId ?? $user->google_id;
        $user->apple_id = $data->appleId ?? $user->apple_id;
        $user->twitter_id = $data->twitterId ?? $user->twitter_id;
        $user->picture = $data->picture ?? $user->picture ?? 'https://foreverpet.com/content/users/default.png';
        $user->terms_and_conditions_accepted = true;
        $user->terms_and_conditions_accepted_date = now();
        $user->privacy_accepted = true;
        $user->privacy_accepted_date = now();

        $this->handlePhoneVerification($user, $data);

        $user->save();
    }

    private function createUser(UserRegisterData $data): User
    {
        $user = new User();
        $user->id = Str::uuid()->toString();
        $user->name = $data->name ?: 'Sem nome';
        $user->email = strtolower($data->email);
        $user->password = $data->password ? Hash::make($data->password) : 'USE_OTP';
        $user->picture = 'https://foreverpet.com/content/users/default.png';
        $user->status = 0; // NotConfirmed
        $user->facebook_id = $data->facebookId;
        $user->google_id = $data->googleId;
        $user->apple_id = $data->appleId;
        $user->twitter_id = $data->twitterId;
        $user->terms_and_conditions_accepted = true;
        $user->terms_and_conditions_accepted_date = now();
        $user->privacy_accepted = true;
        $user->privacy_accepted_date = now();
        $user->created_at = now();
        $user->last_update_at = now();
        $user->last_visit_at = now();

        $this->handlePhoneVerification($user, $data);

        $user->save();

        return $user;
    }

    private function handlePhoneVerification(User $user, UserRegisterData $data): void
    {
        if (empty($data->phoneNumber)) {
            return;
        }

        $user->phone_number_country_code = $data->phoneNumberCountryCode ?? '55';
        $user->phone_number = $data->phoneNumber;

        // Verify challenge if provided
        if (!empty($data->phoneNumberChallenge) && !empty($data->phoneNumberConfirmationCode)) {
            try {
                // TODO: Implement decryption logic
                // $decrypted = decrypt($data->phoneNumberChallenge);
                // if ($data->phoneNumberConfirmationCode === $decrypted[0]) {
                //     $user->phone_number_confirmed = true;
                // }
            } catch (\Exception $e) {
                $user->phone_number_confirmed = false;
            }
        }
    }
}

