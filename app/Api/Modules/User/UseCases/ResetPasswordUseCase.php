<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserPasswordResetData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\NotFoundException;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

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

        // Generate reset URL
        $resetUrl = config('app.frontend_url') . '/reset-password?token=' . urlencode($token);

        // Send password reset email
        Mail::to($user->email)->send(
            new PasswordResetMail($user->name, $resetUrl)
        );
    }

    private function generateToken(string $email): string
    {
        $data = $email . '|' . now()->toIso8601String();
        return Crypt::encryptString($data);
    }
}

