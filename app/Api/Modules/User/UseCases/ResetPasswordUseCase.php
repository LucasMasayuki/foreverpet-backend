<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserPasswordResetData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Crypt;

class ResetPasswordUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserPasswordResetData $data): void
    {
        $user = $this->repository->findByEmail($data->email);

        if (!$user) {
            // Não revelar se o email existe ou não (segurança)
            return;
        }

        // Generate password reset token
        $token = $this->generateToken($user->email);

        $user->password_reset_token = $token;
        $user->last_update_at = now();
        $user->save();

        // TODO: Dispatch SendPasswordResetEmailJob
        // dispatch(new SendPasswordResetEmailJob($user, $token));
    }

    private function generateToken(string $email): string
    {
        $data = $email . '|' . now()->toIso8601String();
        return Crypt::encryptString($data);
    }
}

