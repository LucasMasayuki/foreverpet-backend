<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Str;

class SocialLoginUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(array $socialData): array
    {
        $provider = $socialData['provider']; // facebook, google, apple, twitter
        $providerId = $socialData['provider_id'];
        $email = $socialData['email'] ?? null;
        $name = $socialData['name'] ?? 'Sem nome';
        $picture = $socialData['picture'] ?? null;

        // Find or create user
        $user = $this->findOrCreateSocialUser($provider, $providerId, $email, $name, $picture);

        // Check if phone is blocked
        if ($user->block_phone ||
            $this->repository->isPhoneBlocked($user->phone_number ?? '', $user->phone_number_country_code ?? '')) {
            throw new ValidationException('Acesso bloqueado.');
        }

        // Update last login
        $user->last_login_at = now();
        $user->last_visit_at = now();
        $user->save();

        // Generate token
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'is_new_user' => $user->wasRecentlyCreated,
        ];
    }

    private function findOrCreateSocialUser(
        string $provider,
        string $providerId,
        ?string $email,
        string $name,
        ?string $picture
    ): User {
        $providerField = $this->getProviderField($provider);

        // Try to find by provider ID
        $user = User::where($providerField, $providerId)
            ->whereNotIn('status', [2, 3]) // OptedOut, Removed
            ->first();

        if ($user) {
            // Update picture if provided
            if ($picture && $user->picture !== $picture) {
                $user->picture = $picture;
                $user->save();
            }
            return $user;
        }

        // Try to find by email
        if ($email) {
            $user = $this->repository->findByEmail($email);

            if ($user) {
                // Link social account to existing user
                $user->{$providerField} = $providerId;
                if ($picture) {
                    $user->picture = $picture;
                }
                $user->terms_and_conditions_accepted = true;
                $user->terms_and_conditions_accepted_date = now();
                $user->privacy_accepted = true;
                $user->privacy_accepted_date = now();
                $user->save();

                return $user;
            }
        }

        // Create new user
        $user = new User();
        $user->id = Str::uuid()->toString();
        $user->name = $name;
        $user->email = $email ? strtolower($email) : $providerId . '@' . $provider . '.social';
        $user->password = 'USE_OTP'; // No password for social users
        $user->{$providerField} = $providerId;
        $user->picture = $picture ?? 'https://foreverpet.com/content/users/default.png';
        $user->status = 1; // Confirmed
        $user->terms_and_conditions_accepted = true;
        $user->terms_and_conditions_accepted_date = now();
        $user->privacy_accepted = true;
        $user->privacy_accepted_date = now();
        $user->created_at = now();
        $user->last_update_at = now();
        $user->last_visit_at = now();
        $user->save();

        return $user;
    }

    private function getProviderField(string $provider): string
    {
        return match($provider) {
            'facebook' => 'facebook_id',
            'google' => 'google_id',
            'apple' => 'apple_id',
            'twitter' => 'twitter_id',
            default => throw new ValidationException('Provider não suportado: ' . $provider),
        };
    }
}

