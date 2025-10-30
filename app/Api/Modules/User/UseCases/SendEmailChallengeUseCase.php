<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserChallengeData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Mail\ChallengeCodeMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

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

        // Send email with challenge code
        $purpose = $data->type === 1 ? 'fazer login' : 'verificar sua conta';

        Mail::to($user->email)->send(
            new ChallengeCodeMail($user->name, $code, $purpose)
        );

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

