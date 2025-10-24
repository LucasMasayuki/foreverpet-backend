<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserQueryData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Models\User;

class GetUserUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserQueryData $query): User
    {
        return $this->repository->findById($query->id);
    }
}

