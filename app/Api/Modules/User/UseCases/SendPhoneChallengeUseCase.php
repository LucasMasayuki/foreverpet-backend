<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserChallengeData;
use App\Api\Modules\User\Repositories\UsersRepository;
use Illuminate\Support\Facades\Crypt;

class SendPhoneChallengeUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserChallengeData $data): array
    {
        // Generate 4-digit code
        $code = $this->generateCode();

        // If type is Login, update user SMS challenge
        if ($data->type === 1) { // Login
            $user = $this->repository->findByPhoneNumber(
                $data->phoneNumber,
                $data->phoneNumberCountryCode
            );

            if (!$user) {
                return ['error' => 'invalid_credentials'];
            }

            $user->sms_challenge_code = $code;
            $user->sms_challenge_code_sent_at = now();
            $user->save();
        }

        // Send SMS
        $message = $data->type === 1 ?
            "ForeverPet: utilize o codigo {$code} para verificar seu telefone. " . ($data->appHash ?? '') :
            "ForeverPet: utilize o codigo {$code} para autorizar seu acesso. " . ($data->appHash ?? '');

        // TODO: Integrate with SMS service
        // $this->smsService->send($data->phoneNumberCountryCode ?? '55', $data->phoneNumber, $message);

        // Encrypt challenge
        $challenge = Crypt::encryptString(
            $code . '|' .
            $data->phoneNumber . '|' .
            ($data->phoneNumberCountryCode ?? '55')
        );

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

