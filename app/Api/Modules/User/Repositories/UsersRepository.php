<?php

namespace App\Api\Modules\User\Repositories;

use App\Api\Modules\User\Data\UserData;
use App\Api\Modules\User\Data\UsersQueryData;
use App\Api\Support\Contracts\RepositoryContract;
use App\Api\Support\Repository\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

/**
 * @extends BaseRepository<\App\Models\User>
 *
 * @implements RepositoryContract<\App\Models\User>
 */
class UsersRepository extends BaseRepository implements RepositoryContract
{
    public function model(): string
    {
        return User::class;
    }

    public function query(): Builder
    {
        return $this->makeModel()->newQuery();
    }

    public function getAll(UsersQueryData $filters): LengthAwarePaginator
    {
        return $this->query()
            ->when($filters->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($filters->sortBy, $filters->sortDirection)
            ->paginate($filters->perPage, ['*'], 'page', $filters->page);
    }

    public function findById(int $id): User
    {
        return $this->query()->findOrFail($id);
    }

    public function create(UserData $data): User
    {
        $userData = $data->toArrayModel();

        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        return $this->query()->create($userData);
    }

    public function update(int $id, UserData $data): User
    {
        $user = $this->findById($id);
        $userData = $data->toArrayModel();

        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        return $user->delete();
    }
}

