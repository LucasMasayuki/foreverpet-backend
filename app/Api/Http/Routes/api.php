<?php

use App\Api\Modules\User\Controllers\UserAuthController;
use App\Api\Modules\User\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Authenticated
|--------------------------------------------------------------------------
|
| Rotas da API que requerem autenticação
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Module (Authenticated Routes)
    |--------------------------------------------------------------------------
    */

    Route::prefix('rest')->group(function () {
        // User Profile
        Route::get('/Users', [UserProfileController::class, 'show']);
        Route::get('/Users/{id}', [UserProfileController::class, 'getBasicInfo']);
        Route::post('/Users', [UserProfileController::class, 'update']);

        // Password Management
        Route::post('/Users/ChangePassword', [UserAuthController::class, 'changePassword']);

        // Phone Number
        Route::post('/Users/PhoneNumber', [UserAuthController::class, 'updatePhoneNumber']);

        // QR Code
        Route::post('/Users/QRCode/Scan', [UserProfileController::class, 'scanQrCode']);

        // Devices
        Route::post('/Users/Devices', [UserProfileController::class, 'updateDevice']);

        // Terms & Conditions
        Route::post('/Users/AcceptTermsAndPrivacy', [UserAuthController::class, 'acceptTerms']);

        // S3 Upload
        Route::post('/Users/CreateSignedUploadUrl', [UserProfileController::class, 'createSignedUploadUrl']);
    });

    // Legacy User CRUD (for admin/internal use)
    Route::apiResource('users', \App\Api\Modules\User\Controllers\UsersController::class);

    // Adicione mais rotas de módulos aqui
    // Route::apiResource('products', \App\Api\Modules\Product\Controllers\ProductsController::class);
});
