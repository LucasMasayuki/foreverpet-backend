<?php

namespace App\Api\Support\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = 'Não autorizado', int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error' => 'unauthorized',
        ], $this->getCode());
    }
}

