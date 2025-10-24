<?php

namespace App\Api\Support\Exceptions;

use App\Api\Support\Dictionaries\ErrorCode;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;

class ValidationErrorHandling
{
    public const NOT_FOUND = 'NotFound';

    public const UNIQUE = 'Unique';

    public static function render(ValidationException $exception): array
    {
        $errors = [];

        foreach ($exception->validator->failed() as $field => $types) {
            $errors[] = self::parsePayload($field, $types);
        }

        return [
            'code' => strtolower(ErrorCode::UNPROCESSABLE_ENTITY),
            'message' => 'Invalid Validation',
            'meta' => $errors,
        ];
    }

    /**
     * Formato de retorno de erro de validação
     *
     * @return string[]
     */
    private static function parsePayload(string $field, array $types): array
    {
        $validations = [];

        foreach ($types as $type => $values) {
            if (method_exists($type, 'translateType')) {
                $type = app($type)->translateType($values);
            } else {
                $type = str_replace('App\\Rules\\', '', $type);
                $type = str_replace('App\\Api\\Support\\Rules\\', '', $type);
                $type = str_replace('Illuminate\\Validation\\Rules\\', '', $type);
            }

            [$type, $values] = self::resolveTypes($type, $values);

            $validations[] = [
                'type' => $type,
                'value' => $values,
            ];
        }

        return [
            'field' => Str::snake($field),
            'validations' => $validations,
        ];
    }

    /**
     * Resolve alguns tipos validação para que não retornar informações desnecessárias.
     */
    private static function resolveTypes(string $type, array $values): array
    {
        $trimValues = collect($values)->map(fn ($item) => trim($item));

        /**
         * A validação Exists retorna a connection completa da tabela no values, portanto precisando tratar
         */
        return match ($type) {
            class_basename(Exists::class) => [self::NOT_FOUND, []],
            class_basename(Unique::class) => [self::UNIQUE, []],
            default => [$type, $trimValues]
        };
    }
}

