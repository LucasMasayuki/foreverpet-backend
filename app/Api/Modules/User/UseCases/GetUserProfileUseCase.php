<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Support\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetUserProfileUseCase
{
    public function __construct(
        private \App\Api\Modules\User\Repositories\UsersRepository $repository
    ) {}

    public function execute(): User
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        // Check if phone is blocked
        if ($user->block_phone ||
            $this->repository->isPhoneBlocked($user->phone_number, $user->phone_number_country_code)) {
            throw new UnauthorizedException('Acesso bloqueado.');
        }

        return $user;
    }
}

