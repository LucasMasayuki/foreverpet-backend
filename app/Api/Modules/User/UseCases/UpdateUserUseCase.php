<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Models\User;

class UpdateUserUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserData $data): User
    {
        return $this->repository->update($data->id, $data);
    }
}

