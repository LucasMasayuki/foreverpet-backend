<?php

namespace App\Api\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class ApiBaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Retorna resposta de sucesso padrão
     */
    protected function successResponse($data = null, string $message = 'Success', int $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Retorna resposta de erro padrão
     */
    protected function errorResponse(string $message = 'Error', int $status = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}

