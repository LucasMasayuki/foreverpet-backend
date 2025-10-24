<?php

namespace App\Api\Support\Exceptions;

use App\Api\Support\Contracts\CustomApiException;
use App\Api\Support\Dictionaries\ErrorCode;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnprocessableException extends Exception implements CustomApiException
{
    private ?ErrorCode $errorCode = null;

    public function __construct(
        string $message,
        ?ErrorCode $errorCode = null,

    ) {
        $this->errorCode = $errorCode;
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function getErrorCode(): string
    {
        $code = $this->errorCode ?? ErrorCode::BadRequest;

        return $code->value;
    }

    public function getMeta(): array
    {
        return [];
    }
}

