<?php

use App\Api\Modules\User\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Public
|--------------------------------------------------------------------------
|
| Rotas da API que não requerem autenticação
|
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Versão da API
Route::get('/version', function () {
    return response()->json([
        'version' => '1.0.0',
        'laravel' => app()->version(),
    ]);
});

/*
|--------------------------------------------------------------------------
| User Authentication Routes (Public)
|--------------------------------------------------------------------------
*/

Route::prefix('rest')->group(function () {
    // Authentication
    Route::post('/token', [UserAuthController::class, 'token']);
    Route::post('/auth/social', [UserAuthController::class, 'socialLogin']);
    Route::put('/Users', [UserAuthController::class, 'register']);
    Route::post('/Users/Check', [UserAuthController::class, 'checkExists']);

    // Password Management
    Route::post('/Users/ResetPassword', [UserAuthController::class, 'resetPassword']);
    Route::post('/Users/CreatePassword', [UserAuthController::class, 'createPassword']);
    Route::get('/Users/VerifyAccount/{token}', [UserAuthController::class, 'verifyAccount']);

    // Challenges (Phone & Email verification)
    Route::post('/Users/Challenge/Phone', [UserAuthController::class, 'phoneChallenge']);
    Route::post('/Users/Challenge/Email', [UserAuthController::class, 'emailChallenge']);
    Route::post('/Users/Challenge/Validate', [UserAuthController::class, 'validateChallenge']);

    // Health check
    Route::get('/Users/Ping', [UserAuthController::class, 'ping']);
});
