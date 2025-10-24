<?php

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
    // User Module
    Route::apiResource('users', \App\Api\Modules\User\Controllers\UsersController::class);

    // Adicione mais rotas de módulos aqui
    // Route::apiResource('products', \App\Api\Modules\Product\Controllers\ProductsController::class);
});
