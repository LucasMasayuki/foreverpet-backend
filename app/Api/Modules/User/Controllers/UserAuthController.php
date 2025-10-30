<?php

namespace App\Api\Modules\User\Controllers;

use App\Api\Http\Controllers\ApiBaseController;
use App\Api\Modules\User\Data\SocialLoginData;
use App\Api\Modules\User\Data\UserChangePasswordData;
use App\Api\Modules\User\Data\UserChallengeData;
use App\Api\Modules\User\Data\UserChallengeValidateData;
use App\Api\Modules\User\Data\UserCreatePasswordData;
use App\Api\Modules\User\Data\UserLoginData;
use App\Api\Modules\User\Data\UserPasswordResetData;
use App\Api\Modules\User\Data\UserPhoneNumberData;
use App\Api\Modules\User\Data\UserRegisterData;
use App\Api\Modules\User\Resource\UserProfileResource;
use App\Api\Modules\User\UseCases\AcceptTermsUseCase;
use App\Api\Modules\User\UseCases\ChangePasswordUseCase;
use App\Api\Modules\User\UseCases\CheckUserExistsUseCase;
use App\Api\Modules\User\UseCases\CreatePasswordUseCase;
use App\Api\Modules\User\UseCases\LoginUserUseCase;
use App\Api\Modules\User\UseCases\RegisterUserUseCase;
use App\Api\Modules\User\UseCases\ResetPasswordUseCase;
use App\Api\Modules\User\UseCases\SendEmailChallengeUseCase;
use App\Api\Modules\User\UseCases\SendPhoneChallengeUseCase;
use App\Api\Modules\User\UseCases\SocialLoginUseCase;
use App\Api\Modules\User\UseCases\UpdatePhoneNumberUseCase;
use App\Api\Modules\User\UseCases\ValidateChallengeUseCase;
use App\Api\Modules\User\UseCases\VerifyAccountUseCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserAuthController extends ApiBaseController
{
    /**
     * POST /rest/token - Login (generate JWT)
     *
     * Supports both username/password and social login
     */
    public function token(UserLoginData $data, LoginUserUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($data);

        return response()->json([
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
            'user' => new UserProfileResource($result['user']),
        ]);
    }

    /**
     * POST /rest/auth/social - Social Login (OAuth)
     *
     * Login or register with social providers (Facebook, Google, Apple, Twitter)
     */
    public function socialLogin(SocialLoginData $data, SocialLoginUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($data->toArray());

        return response()->json([
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
            'user' => new UserProfileResource($result['user']),
            'is_new_user' => $result['is_new_user'],
        ]);
    }

    /**
     * PUT /rest/Users - Register new user
     */
    public function register(UserRegisterData $data, RegisterUserUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($data);

        return response()->json($result);
    }

    /**
     * POST /rest/Users/Check - Check if user exists
     */
    public function checkExists(UserRegisterData $data, CheckUserExistsUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($data);

        return response()->json($result);
    }

    /**
     * POST /rest/Users/ResetPassword - Request password reset
     */
    public function resetPassword(UserPasswordResetData $data, ResetPasswordUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([
            'message' => 'Em instantes você receberá um link para redefinir sua senha.',
        ]);
    }

    /**
     * POST /rest/Users/CreatePassword - Create password with token
     */
    public function createPassword(UserCreatePasswordData $data, CreatePasswordUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([
            'message' => 'Senha registrada com sucesso.',
        ]);
    }

    /**
     * GET /rest/Users/VerifyAccount/{token} - Verify account
     */
    public function verifyAccount(string $token, VerifyAccountUseCase $useCase): JsonResponse
    {
        $useCase->execute($token);

        return response()->json([
            'message' => 'Conta verificada com sucesso.',
        ]);
    }

    /**
     * POST /rest/Users/ChangePassword - Change current password
     */
    public function changePassword(UserChangePasswordData $data, ChangePasswordUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([
            'message' => 'Senha alterada com sucesso.',
        ]);
    }

    /**
     * POST /rest/Users/PhoneNumber - Update phone number
     */
    public function updatePhoneNumber(UserPhoneNumberData $data, UpdatePhoneNumberUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([
            'message' => 'Telefone salvo com sucesso.',
        ]);
    }

    /**
     * POST /rest/Users/Challenge/Phone - Send SMS challenge
     */
    public function phoneChallenge(UserChallengeData $data, SendPhoneChallengeUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($data);

        return response()->json($result);
    }

    /**
     * POST /rest/Users/Challenge/Email - Send email challenge
     */
    public function emailChallenge(UserChallengeData $data, SendEmailChallengeUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($data);

        return response()->json($result);
    }

    /**
     * POST /rest/Users/Challenge/Validate - Validate challenge
     */
    public function validateChallenge(UserChallengeValidateData $data, ValidateChallengeUseCase $useCase): JsonResponse
    {
        $useCase->execute($data);

        return response()->json([]);
    }

    /**
     * POST /rest/Users/AcceptTermsAndPrivacy - Accept terms
     */
    public function acceptTerms(AcceptTermsUseCase $useCase): JsonResponse
    {
        $useCase->execute();

        return response()->json([
            'message' => 'Termos de uso e política de privacidade aceitos com sucesso.',
        ]);
    }

    /**
     * GET /rest/Users/Ping - Health check
     */
    public function ping(): JsonResponse
    {
        return response()->json('OK');
    }
}

