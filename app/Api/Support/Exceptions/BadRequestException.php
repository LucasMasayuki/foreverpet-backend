<?php

namespace App\Api\Support\Exceptions;

use App\Api\Support\Contracts\CustomApiException;
use App\Api\Support\Dictionaries\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends \Exception implements CustomApiException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return ErrorCode::BAD_REQUEST;
    }

    public function getMeta(): array
    {
        return [];
    }
}

