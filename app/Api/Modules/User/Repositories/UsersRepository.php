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

    // New methods for authentication and user management

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->query()
            ->where('email', strtolower($email))
            ->whereNotIn('status', [2, 3]) // OptedOut, Removed
            ->first();
    }

    /**
     * Find user by social login or email
     */
    public function findBySocialOrEmail($data): ?User
    {
        return $this->query()
            ->where(function (Builder $query) use ($data) {
                $query->where('email', strtolower($data->email));

                if (!empty($data->facebookId)) {
                    $query->orWhere('facebook_id', $data->facebookId);
                }
                if (!empty($data->googleId)) {
                    $query->orWhere('google_id', $data->googleId);
                }
                if (!empty($data->appleId)) {
                    $query->orWhere('apple_id', $data->appleId);
                }
                if (!empty($data->twitterId)) {
                    $query->orWhere('twitter_id', $data->twitterId);
                }
            })
            ->whereNotIn('status', [2, 3]) // OptedOut, Removed
            ->first();
    }

    /**
     * Check if email already exists
     */
    public function emailExists(string $email): bool
    {
        return $this->query()
            ->where('email', strtolower($email))
            ->whereNotIn('status', [2, 3])
            ->exists();
    }

    /**
     * Find user by phone number
     */
    public function findByPhoneNumber(string $phoneNumber, ?string $countryCode = null): ?User
    {
        $cleanPhone = str_replace([' ', '(', ')', '-'], '', $phoneNumber);

        return $this->query()
            ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone_number, ' ', ''), '(', ''), ')', ''), '-', '') = ?", [$cleanPhone])
            ->when($countryCode, function (Builder $query, $code) {
                $query->where(function (Builder $q) use ($code) {
                    $q->where('phone_number_country_code', $code)
                      ->orWhereNull('phone_number_country_code');
                });
            })
            ->whereNotIn('status', [2, 3])
            ->orderBy('last_visit_at', 'desc')
            ->first();
    }

    /**
     * Check if phone is blocked
     */
    public function isPhoneBlocked(string $phoneNumber, ?string $countryCode = null): bool
    {
        $cleanPhone = str_replace([' ', '(', ')', '-'], '', $phoneNumber);

        return $this->query()
            ->where('block_phone', true)
            ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone_number, ' ', ''), '(', ''), ')', ''), '-', '') = ?", [$cleanPhone])
            ->when($countryCode, function (Builder $query, $code) {
                $query->where('phone_number_country_code', $code);
            })
            ->exists();
    }
}

