<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserChallengeValidateData;
use App\Api\Support\Exceptions\ValidationException;
use Illuminate\Support\Facades\Crypt;

class ValidateChallengeUseCase
{
    public function execute(UserChallengeValidateData $data): void
    {
        // Dev bypass
        if ($data->emailOrPhoneNumber === 'dev@foreverpet.com' && $data->confirmationCode === '9999') {
            return;
        }

        try {
            $decrypted = Crypt::decryptString($data->challenge);
            [$code, $emailOrPhone] = explode('|', $decrypted);

            $cleanInput = $this->cleanPhoneNumber($data->emailOrPhoneNumber);
            $cleanStored = $this->cleanPhoneNumber($emailOrPhone);

            if ($data->confirmationCode !== $code || $cleanInput !== $cleanStored) {
                throw new \Exception('Invalid challenge');
            }
        } catch (\Exception $e) {
            throw new ValidationException('Código de validação inválido.');
        }
    }

    private function cleanPhoneNumber(string $value): string
    {
        return str_replace([' ', '(', ')', '-'], '', $value);
    }
}

