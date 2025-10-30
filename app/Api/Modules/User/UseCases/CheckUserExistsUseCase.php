<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserRegisterData;
use App\Api\Modules\User\Repositories\UsersRepository;

class CheckUserExistsUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserRegisterData $data): array
    {
        $user = $this->repository->findBySocialOrEmail($data);

        return [
            'existing' => $user !== null,
            'has_phone_number_confirmed' => $user?->phone_number_confirmed ?? false,
        ];
    }
}

