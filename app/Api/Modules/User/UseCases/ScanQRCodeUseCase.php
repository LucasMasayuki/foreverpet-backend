<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserQRCodeScanData;
use App\Api\Support\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ScanQRCodeUseCase
{
    public function execute(UserQRCodeScanData $data): void
    {
        try {
            $decrypted = Crypt::decryptString($data->content);

            if (!str_ends_with($decrypted, '|OK')) {
                throw new ValidationException('QR Code inválido.');
            }

            /** @var User $user */
            $user = Auth::guard('api')->user();

            $key = explode('|', $decrypted)[0];
            $user->qr_code_login_key = $key;
            $user->save();

        } catch (\Exception $e) {
            throw new ValidationException('QR Code inválido.');
        }
    }
}

