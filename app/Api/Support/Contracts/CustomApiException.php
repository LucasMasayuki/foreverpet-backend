<?php

namespace App\Api\Support\Contracts;

interface CustomApiException
{
    public function getMessage();

    public function getErrorCode(): string;

    public function getStatusCode(): int;

    public function getMeta(): array;
}

