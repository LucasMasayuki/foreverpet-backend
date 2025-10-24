<?php

namespace App\Api\Support\Exceptions;

use App\Api\Support\Contracts\CustomApiException;
use Exception;

abstract class CustomException extends Exception implements CustomApiException
{
    protected $metaData = [];

    protected string $validationType = 'NotFound';

    public function setMeta(array $data): self
    {
        $this->metaData = $data;

        return $this;
    }

    public function setValidationType(string $validationType): self
    {
        $this->validationType = $validationType;

        return $this;
    }

    public function getMeta(): array
    {
        $meta = [];

        foreach ($this->metaData as $key => $value) {
            $meta[] = [
                'field' => $key,
                'validations' => [
                    [
                        'type' => $this->validationType,
                        'value' => [
                            $value,
                        ],
                    ],
                ],
            ];
        }

        return $meta;
    }
}

