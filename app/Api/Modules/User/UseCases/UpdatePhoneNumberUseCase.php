<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserPhoneNumberData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UpdatePhoneNumberUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserPhoneNumberData $data): void
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        // Check if phone is blocked
        if ($this->repository->isPhoneBlocked($data->phoneNumber, $data->phoneNumberCountryCode)) {
            throw new ValidationException('Phone is blocked. Please contact our support.');
        }

        // Verify confirmation code if provided
        if (!empty($data->phoneNumberConfirmationCode)) {
            $this->verifyCode($user, $data);
        } else {
            $user->phone_number_confirmed = false;
        }

        $user->phone_number_country_code = $data->phoneNumberCountryCode ?? '55';
        $user->phone_number = $data->phoneNumber;
        $user->last_update_at = now();
        $user->save();
    }

    private function verifyCode(User $user, UserPhoneNumberData $data): void
    {
        if (!empty($data->challenge)) {
            // Verify with challenge
            try {
                $decrypted = Crypt::decryptString($data->challenge);
                [$code, $phone] = explode('|', $decrypted);

                // Dev bypass
                if ($data->email === 'dev@foreverpet.com' && $data->phoneNumberConfirmationCode === '9999') {
                    $user->phone_number_confirmed = true;
                    return;
                }

                $cleanDataPhone = $this->cleanPhone($data->phoneNumber);
                $cleanStoredPhone = $this->cleanPhone($phone);

                if ($data->phoneNumberConfirmationCode === $code && $cleanDataPhone === $cleanStoredPhone) {
                    $user->phone_number_confirmed = true;
                }
            } catch (\Exception $e) {
                // Invalid challenge
            }
        } else {
            // Verify with stored code
            if ($data->phoneNumberConfirmationCode === $user->phone_number_confirmation_code) {
                $user->phone_number_confirmed = true;
            } else {
                throw new ValidationException('Código de confirmação inválido.');
            }
        }
    }

    private function cleanPhone(string $phone): string
    {
        return str_replace([' ', '(', ')', '-'], '', $phone);
    }
}

