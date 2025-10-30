<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserChallengeData;
use App\Api\Modules\User\Repositories\UsersRepository;
use Illuminate\Support\Facades\Crypt;

class SendEmailChallengeUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserChallengeData $data): array
    {
        $user = $this->repository->findByEmail($data->email);

        if (!$user) {
            return ['error' => 'invalid_credentials'];
        }

        // Generate 4-digit code
        $code = $this->generateCode();

        // Update user
        $user->email_challenge_code = $code;
        $user->email_challenge_code_sent_at = now();
        $user->save();

        // Send email
        if ($user->email !== 'dev@foreverpet.com') {
            // TODO: Send email with template
            // Mail::send('mail_user_challenge', ['Nome' => $user->name, 'Code' => $code], ...);
        }

        // Encrypt challenge
        $challenge = Crypt::encryptString($code . '|' . $data->email);

        return ['challenge' => $challenge];
    }

    private function generateCode(): string
    {
        return str_pad((string) abs(crc32(uniqid())), 4, '8')[0] .
               str_pad((string) abs(crc32(uniqid())), 4, '8')[1] .
               str_pad((string) abs(crc32(uniqid())), 4, '8')[2] .
               str_pad((string) abs(crc32(uniqid())), 4, '8')[3];
    }
}

