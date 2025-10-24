<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserQueryData;
use App\Api\Modules\User\Repositories\UsersRepository;

class DeleteUserUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserQueryData $query): bool
    {
        return $this->repository->delete($query->id);
    }
}

