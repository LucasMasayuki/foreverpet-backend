<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UsersQueryData;
use App\Api\Modules\User\Repositories\UsersRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllUsersUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UsersQueryData $query): LengthAwarePaginator
    {
        return $this->repository->getAll($query);
    }
}

