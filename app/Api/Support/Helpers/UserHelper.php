<?php

namespace App\Api\Support\Helpers;

use App\Api\Support\Enums\SessionEnum;
use Illuminate\Support\Facades\Auth;

class UserHelper
{
    /**
     * Retorna o ID do usuário autenticado
     */
    public static function getUserId(): ?int
    {
        return Auth::id() ?? session(SessionEnum::UserId->value);
    }

    /**
     * Retorna o email do usuário autenticado
     */
    public static function getUserEmail(): ?string
    {
        return Auth::user()?->email ?? session(SessionEnum::UserEmail->value);
    }

    /**
     * Retorna a empresa selecionada na sessão
     */
    public static function getCompanyId(): ?int
    {
        return session(SessionEnum::CompanySelected->value);
    }

    /**
     * Verifica se o usuário está autenticado
     */
    public static function isAuthenticated(): bool
    {
        return Auth::check();
    }
}

