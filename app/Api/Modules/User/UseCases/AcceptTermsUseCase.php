<?php

namespace App\Api\Modules\User\UseCases;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AcceptTermsUseCase
{
    public function execute(): void
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();

        $user->terms_and_conditions_accepted = true;
        $user->terms_and_conditions_accepted_date = now();
        $user->privacy_accepted = true;
        $user->privacy_accepted_date = now();
        $user->last_update_at = now();
        $user->save();
    }
}

