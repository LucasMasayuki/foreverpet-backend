<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserCreatePasswordData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CreatePasswordUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserCreatePasswordData $data): void
    {
        $user = $this->getUserByToken($data->token);

        if (!$user || $user->password_reset_token !== $data->token) {
            throw new ValidationException('Token inválido.');
        }

        $user->password = Hash::make($data->password);
        $user->password_reset_token = null;

        // Confirm account if not confirmed yet
        if ($user->status === 0) { // NotConfirmed
            $user->status = 1; // Confirmed
        }

        $user->save();
    }

    private function getUserByToken(string $token): ?\App\Models\User
    {
        try {
            $decrypted = Crypt::decryptString($token);
            [$email, $dateString] = explode('|', $decrypted);

            $date = new \DateTime($dateString);

            // Check if token is expired (24 hours)
            if ($date < now()->subDay()) {
                return null;
            }

            return $this->repository->findByEmail($email);
        } catch (\Exception $e) {
            return null;
        }
    }
}

