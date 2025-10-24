<?php

namespace App\Api\Support\Exceptions;

use App\Api\Support\Contracts\CustomApiException;
use App\Api\Support\Dictionaries\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class UneditableEntityException extends CustomException implements CustomApiException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function getErrorCode(): string
    {
        return ErrorCode::UNEDITABLE_ENTITY;
    }
}

