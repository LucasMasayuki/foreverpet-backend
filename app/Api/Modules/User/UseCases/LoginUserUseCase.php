<?php

namespace App\Api\Modules\User\UseCases;

use App\Api\Modules\User\Data\UserLoginData;
use App\Api\Modules\User\Repositories\UsersRepository;
use App\Api\Support\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginUserUseCase
{
    public function __construct(
        private UsersRepository $repository
    ) {}

    public function execute(UserLoginData $data): array
    {
        // Try to find user by email or phone
        $user = $this->findUser($data->username);

        if (!$user) {
            throw new UnauthorizedException('Credenciais inválidas.');
        }

        // Check if phone is blocked
        if ($user->block_phone ||
            $this->repository->isPhoneBlocked($user->phone_number, $user->phone_number_country_code)) {
            throw new UnauthorizedException('Acesso bloqueado.');
        }

        // Verify password
        if (!Hash::check($data->password, $user->password)) {
            throw new UnauthorizedException('Credenciais inválidas.');
        }

        // Check if user needs to be logged off
        if ($user->logoff_required) {
            throw new UnauthorizedException('É necessário fazer login novamente.');
        }

        // Update last login
        $user->last_login_at = now();
        $user->last_visit_at = now();
        $user->save();

        // Generate token
        $token = $user->createToken('api-token', ['api:access'])->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }

    private function findUser(string $username): ?User
    {
        // Try email first
        $user = $this->repository->findByEmail($username);

        if ($user) {
            return $user;
        }

        // Try phone number
        $cleanPhone = str_replace([' ', '(', ')', '-'], '', $username);
        return $this->repository->findByPhoneNumber($cleanPhone);
    }
}

