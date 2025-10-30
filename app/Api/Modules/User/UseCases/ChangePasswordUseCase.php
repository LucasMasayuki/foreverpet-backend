<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserChangePasswordData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserChangePasswordData $data): void
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        // Verify current password
        if (!Hash::check($data->currentPassword, $user->password)) {
            throw new ValidationException('Senha atual incorreta.');
        }

        $user->password = Hash::make($data->newPassword);
        $user->last_update_at = now();
        $user->save();
    }
}

