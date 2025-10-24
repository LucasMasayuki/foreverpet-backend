<?php

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
