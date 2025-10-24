<?php

namespace App\Api\Modules\User\Controllers;

use App\Api\Http\Controllers\ApiBaseController;
use App\Api\Modules\User\Data\UserData;
use App\Api\Modules\User\Data\UserQueryData;
use App\Api\Modules\User\Data\UsersQueryData;
use App\Api\Modules\User\Resource\UserResource;
use App\Api\Modules\User\Resource\UsersResource;
use App\Api\Modules\User\UseCases\CreateUserUseCase;
use App\Api\Modules\User\UseCases\DeleteUserUseCase;
use App\Api\Modules\User\UseCases\GetAllUsersUseCase;
use App\Api\Modules\User\UseCases\GetUserUseCase;
use App\Api\Modules\User\UseCases\UpdateUserUseCase;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends ApiBaseController
{
    public function index(UsersQueryData $query, GetAllUsersUseCase $useCase): UsersResource
    {
        $users = $useCase->execute($query);

        return new UsersResource($users);
    }

    public function store(UserData $data, CreateUserUseCase $useCase): UserResource
    {
        $user = $useCase->execute($data);

        return new UserResource($user);
    }

    public function show(UserQueryData $data, GetUserUseCase $useCase): UserResource
    {
        $user = $useCase->execute($data);

        return new UserResource($user);
    }

    public function update(UserData $data, UpdateUserUseCase $useCase): UserResource
    {
        $user = $useCase->execute($data);

        return new UserResource($user);
    }

    public function destroy(UserQueryData $data, DeleteUserUseCase $useCase): \Illuminate\Http\JsonResponse
    {
        $useCase->execute($data);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

