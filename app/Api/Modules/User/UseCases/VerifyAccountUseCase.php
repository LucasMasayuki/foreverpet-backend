<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\ValidationException;
use Illuminate\Support\Facades\Crypt;

class VerifyAccountUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(string $token): void
    {
        $user = $this->getUserByToken($token);

        if (!$user) {
            throw new ValidationException('Token inválido.');
        }

        $user->password_reset_token = null;

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

            return $this->repository->findByEmail($email);
        } catch (\Exception $e) {
            return null;
        }
    }
}

